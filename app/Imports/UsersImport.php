<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Subscription;
use App\Models\StandardLink;
use App\Models\Qualification;
use App\Models\AcademicYear;
use App\Traits\RegisterUser;
use App\Models\Standard;
use App\Models\Section;
use App\Models\Country;
use App\Traits\Common;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * Class UsersImport
 *
 * Handles bulk import of student users from Excel files.
 *
 * This import:
 * - Creates students and parents
 * - Resolves class, section, standard links
 * - Maps qualifications, location, transport, siblings
 * - Uses Common helpers and RegisterUser trait
 *
 * @package App\Imports
 */
class UsersImport implements ToCollection, WithHeadingRow
{
    use RegisterUser;
    use Common;

    /**
     * Validation errors collected during validateImport()
     */
    protected $validationErrors = [];

    /**
     * Warnings collected during import (non-blocking issues like email modifications)
     */
    protected $importWarnings = [];

    /**
     * Import statistics
     */
    protected $parentsCreated = 0;
    protected $siblingsLinked = 0;

    /**
     * Get import summary statistics
     *
     * @return array
     */
    public function getImportSummary()
    {
        return [
            'parents_created' => $this->parentsCreated,
            'siblings_linked' => $this->siblingsLinked,
        ];
    }

    /**
     * Validate the imported data before processing.
     * Returns array of errors if any, empty array if valid.
     *
     * @param \Illuminate\Support\Collection $rows
     * @return array
     */
    public function validateImport(Collection $rows)
    {
        $this->validationErrors = [];
        $this->importWarnings = [];
        $school_id = Auth::user()->school_id;
        $rowNumber = 1; // Start from 1 (after header row)

        // Get all valid classes and sections for this school (cached for performance)
        $validStandards = Standard::where('school_id', $school_id)
            ->pluck('name', 'id')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();
        
        $validSections = Section::where('school_id', $school_id)
            ->pluck('name', 'id')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();

        foreach ($rows as $row)
        {
            $rowNumber++;
            $rowErrors = [];
            $rowWarnings = [];

            // Skip empty rows
            if (empty(trim($row['firstname'] ?? ''))) {
                continue;
            }

            $studentName = trim($row['firstname'] . ' ' . ($row['lastname'] ?? ''));

            // ==========================================
            // STUDENT VALIDATION
            // ==========================================

            // Check student firstname (mandatory)
            if (empty(trim($row['firstname'] ?? ''))) {
                $rowErrors[] = "Student firstname is required";
            }

            // Check class exists
            $classInput = strtolower(trim($row['class'] ?? ''));
            $classFound = false;
            
            if (!empty($classInput)) {
                // Direct match
                if (in_array($classInput, $validStandards)) {
                    $classFound = true;
                }
                // Numeric match
                elseif (is_numeric($row['class']) && in_array((string)intval($row['class']), $validStandards)) {
                    $classFound = true;
                }
                // Roman numeral match
                else {
                    $romanConverted = $this->romanToInteger($row['class']);
                    if ($romanConverted && in_array((string)$romanConverted, $validStandards)) {
                        $classFound = true;
                    }
                }
            }

            if (empty($classInput)) {
                $rowErrors[] = "Class is required";
            } elseif (!$classFound) {
                $rowErrors[] = "Class '{$row['class']}' does not exist in the system";
            }

            // Check section exists
            $sectionInput = strtolower(trim($row['section'] ?? ''));
            if (empty($sectionInput)) {
                $rowErrors[] = "Section is required";
            } elseif (!in_array($sectionInput, $validSections)) {
                $rowErrors[] = "Section '{$row['section']}' does not exist in the system";
            }

            // Check class+section combination exists (StandardLink)
            if ($classFound && !empty($sectionInput) && in_array($sectionInput, $validSections)) {
                $standard = $this->findStandard($school_id, $row['class']);
                $section = Section::where([['school_id', $school_id], ['name', 'LIKE', trim($row['section'])]])->first();
                
                if ($standard && $section) {
                    $standardLink = StandardLink::where([
                        ['school_id', $school_id],
                        ['standard_id', $standard->id],
                        ['section_id', $section->id]
                    ])->first();
                    
                    if (!$standardLink) {
                        $rowErrors[] = "Class '{$row['class']}' with Section '{$row['section']}' combination is not configured";
                    }
                }
            }

            // ==========================================
            // PARENT VALIDATION
            // ==========================================

            // Check parent email and mobile - at least one is required
            $parentEmail = strtolower(trim($row['parent_email'] ?? ''));
            $parentMobile = trim($row['parent_mobile_no'] ?? '');
            
            if (empty($parentEmail) && empty($parentMobile)) {
                $rowErrors[] = "Parent email OR mobile number is required (used for parent identification)";
            }
            
            // Validate email format if provided
            if (!empty($parentEmail) && !filter_var($parentEmail, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = "Parent email '{$row['parent_email']}' is not a valid email format";
            }

            // Check parent firstname (mandatory)
            $parentFirstname = trim($row['parent_firstname'] ?? '');
            if (empty($parentFirstname)) {
                $rowErrors[] = "Parent firstname is required";
            }

            // Check relation (mandatory)
            $relation = trim($row['relation'] ?? '');
            if (empty($relation)) {
                $rowErrors[] = "Parent relation (father/mother/guardian) is required";
            }

            // Check student registration/admission number (mandatory for email generation)
            $registrationNumber = trim($row['admission_number'] ?? '');
            if (empty($registrationNumber)) {
                $rowErrors[] = "Admission number is required (used for student email generation)";
            }

            // ==========================================
            // LOCATION VALIDATION (warnings)
            // ==========================================

            if (!empty($row['country'])) {
                $country = Country::where('name', 'LIKE', '%' . $row['country'] . '%')->first();
                if (!$country) {
                    $rowErrors[] = "Country '{$row['country']}' not found";
                }
            }

            if (!empty($row['state'])) {
                $state = State::where('name', 'LIKE', '%' . $row['state'] . '%')->first();
                if (!$state) {
                    $rowErrors[] = "State '{$row['state']}' not found";
                }
            }

            if (!empty($row['city'])) {
                $city = City::where('name', 'LIKE', '%' . $row['city'] . '%')->first();
                if (!$city) {
                    $rowErrors[] = "City '{$row['city']}' not found";
                }
            }

            // Add row errors to main array
            if (!empty($rowErrors)) {
                $this->validationErrors[] = [
                    'row' => $rowNumber,
                    'student' => $studentName,
                    'errors' => $rowErrors
                ];
            }

            // Add row warnings to warnings array (non-blocking)
            if (!empty($rowWarnings)) {
                $this->importWarnings[] = [
                    'row' => $rowNumber,
                    'student' => $studentName,
                    'warnings' => $rowWarnings
                ];
            }
        }

        return $this->validationErrors;
    }

    /**
     * Get validation errors (for use after validateImport)
     *
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Get import warnings (for use after collection import)
     *
     * @return array
     */
    public function getImportWarnings()
    {
        return $this->importWarnings;
    }

    /**
     * Find a Standard by class name (handles various formats)
     *
     * @param int $school_id
     * @param string $className
     * @return Standard|null
     */
    protected function findStandard($school_id, $className)
    {
        // Normalize: trim whitespace and convert to lowercase
        $classInput = strtolower(trim($className));

        // First try direct match by name (handles prekg, lkg, ukg, etc.)
        $standard = Standard::where([
            ['school_id', $school_id],
            ['name', $classInput]
        ])->first();

        // If not found and input is numeric, try as integer
        if (!$standard && is_numeric($className)) {
            $standard = Standard::where([
                ['school_id', $school_id],
                ['name', (string)intval($className)]
            ])->first();
        }

        // If still not found, try romanToInteger conversion (for V, X, etc.)
        if (!$standard) {
            $classFromRoman = $this->romanToInteger($className);
            if ($classFromRoman) {
                $standard = Standard::where([
                    ['school_id', $school_id],
                    ['name', (string)$classFromRoman]
                ])->first();
            }
        }

        return $standard;
    }

    /**
     * Process the imported Excel rows.
     *
     * For each row:
     * - Resolves academic year, standard, section
     * - Creates student user
     * - Creates or links parent user
     * - Handles qualifications, siblings, transport info
     *
     * Insert count is stored in session.
     *
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        try
        {
            $school_id      = Auth::user()->school_id;
            $academic_year  = AcademicYear::where('school_id', $school_id)->where('status', 1)->first();
            $user_count     = User::ByRole(6)->where('school_id', $school_id)->count();
            $subscription   = Subscription::where('school_id', $school_id)->first();
            $count          = $subscription->plan->no_of_members - $user_count;
            $insertedcount  = 0;

            foreach ($rows as $row)
            {
                // Skip empty rows
                if (empty(trim($row['firstname'] ?? ''))) {
                    continue;
                }

                $array = str_getcsv($row['parent_qualification']);
                $qualification_id = [];
                
                for ($i = 0; $i < count($array); $i++)
                {
                    $arr[$i] = Qualification::where('type', 'ug')
                        ->where('type', 'pg')
                        ->where('display_name', 'LIKE', '%' . $array[$i] . '%')
                        ->pluck('id')
                        ->toArray();

                    $qualification_array[$i] = implode('', $arr[$i]);

                    if ($qualification_array[$i] == null)
                    {
                        $qualification_id[$i] = 1;
                    }
                }

                // ==========================================
                // GENERIC CLASS MATCHING
                // ==========================================
                
                // Use the helper method to find the standard
                $standard = $this->findStandard($school_id, $row['class']);
                
                // Determine class_name for board registration check
                $class_input = strtolower(trim($row['class']));
                if (is_numeric($row['class'])) {
                    $class_name = intval($row['class']);
                } else {
                    $class_name = $this->romanToInteger($row['class']) ?: $class_input;
                }

                // Skip row if class not found (shouldn't happen if validation was run)
                if (!$standard) {
                    Log::warning("Import: Class '{$row['class']}' not found for student '{$row['firstname']}'. Skipping row.");
                    continue;
                }

                $country      = Country::where('name', 'LIKE', '%' . $row['country'] . '%')->first();
                $state        = State::where('name', 'LIKE', '%' . $row['state'] . '%')->first();
                $city         = City::where('name', 'LIKE', '%' . $row['city'] . '%')->first();
                $section      = Section::where([['school_id', $school_id], ['name', 'LIKE', trim($row['section'])]])->first();
                
                // Skip if section not found
                if (!$section) {
                    Log::warning("Import: Section '{$row['section']}' not found for student '{$row['firstname']}'. Skipping row.");
                    continue;
                }

                $standardLink = StandardLink::where([
                    ['school_id', Auth::user()->school_id],
                    ['standard_id', $standard->id],
                    ['section_id', $section->id]
                ])->first();

                // Skip if standardLink not found
                if (!$standardLink) {
                    Log::warning("Import: StandardLink for class '{$row['class']}' section '{$row['section']}' not found for student '{$row['firstname']}'. Skipping row.");
                    continue;
                }

                $student = new \stdClass();
                $parent = new \stdClass();

                $student->firstname             = $row['firstname'];
                $student->lastname              = $row['lastname'];
                $student->mobile_no             = $row['mobile_no'];
                
                // Auto-generate student email from registration number
                // Domain is configurable via STUDENT_EMAIL_DOMAIN in .env
                // Falls back to 'student.local' if not configured
                $registrationNumber = strtolower(trim($row['admission_number'] ?? ''));
                $studentEmailDomain = config('app.student_email_domain', 'student.local');
                $student->email                 = $registrationNumber . '@' . $studentEmailDomain;
                
                $student->gender                = strtolower($row['gender']);
                $student->date_of_birth         = date('Y-m-d', strtotime($row['date_of_birth']));
                $student->blood_group           = $row['blood_group'] == '' ? null : str_replace('ve', '', strtolower($row['blood_group']));
                $student->standard              = $standardLink->id;
                $student->address               = $row['address'];
                $student->city_id               = $city ? $city->id : null;
                $student->state_id              = $state ? $state->id : null;
                $student->country_id            = $country ? $country->id : null;
                $student->pincode               = $row['pincode'];
                $student->birth_place           = $row['birth_place'];
                $student->native_place          = $row['native_place'];
                $student->mother_tongue         = $row['mother_tongue'];
                $student->caste                 = $row['caste'];
                $student->sub_caste             = $row['sub_caste'];
                $student->aadhar_number         = $row['aadhar_number'];
                $student->joining_date          = date('Y-m-d', strtotime($row['joining_date']));
                $student->registration_number   = $row['admission_number'];
                $student->EMIS_number           = $row['emis_number'];
                $student->roll_number           = $row['roll_number'];
                $student->id_card_number        = $row['id_card_number'];

                if (($class_name == 10) || ($class_name == 12))
                {
                    $student->board_registration_number = $row['board_registration_number'];
                }
                else
                {
                    $student->board_registration_number = '';
                }

                $student->mode_of_transport     = $row['mode_of_transport'];
                $student->driver_name           = $row['driver_name'];
                $student->driver_contact_number = $row['driver_contact_number'];
                $student->siblings              = $row['siblings'];
                $student->siblings_count        = $row['siblings_count'];
                $student->sibling_relation      = str_getcsv($row['sibling_relation']);
                $student->sibling_name          = str_getcsv($row['sibling_name']);
                $student->sibling_date_of_birth = str_getcsv($row['sibling_date_of_birth']);
                $student->sibling_standard      = str_getcsv($row['sibling_class']);
                $student->notes                 = $row['notes'];

                // ==========================================
                // PARENT HANDLING - lookup by mobile (primary) OR email (secondary)
                // ==========================================
                
                $parent_email = strtolower(trim($row['parent_email'] ?? ''));
                $parent_mobile = trim($row['parent_mobile_no'] ?? '');
                $parent_status = null;
                $matched_by = null;
                
                // Step 1: Try to find existing parent by mobile number (primary - used for app login)
                if (!empty($parent_mobile)) {
                    $parent_status = User::where([
                        ['school_id', $school_id],
                        ['mobile_no', $parent_mobile],
                        ['usergroup_id', 7]
                    ])->first();
                    
                    if ($parent_status) {
                        $matched_by = 'mobile';
                        // Check if email needs updating
                        if (!empty($parent_email) && $parent_status->email !== $parent_email) {
                            $this->importWarnings[] = [
                                'type' => 'parent_email_updated',
                                'parent_name' => $parent_status->name,
                                'old_email' => $parent_status->email,
                                'new_email' => $parent_email,
                                'matched_by' => 'mobile'
                            ];
                            $parent_status->email = $parent_email;
                            $parent_status->save();
                        }
                    }
                }
                
                // Step 2: If not found by mobile, try email
                if ($parent_status === null && !empty($parent_email)) {
                    $parent_status = User::where([
                        ['school_id', $school_id],
                        ['email', $parent_email],
                        ['usergroup_id', 7]
                    ])->first();
                    
                    if ($parent_status) {
                        $matched_by = 'email';
                        // Check if mobile needs updating
                        if (!empty($parent_mobile) && $parent_status->mobile_no !== $parent_mobile) {
                            $this->importWarnings[] = [
                                'type' => 'parent_mobile_updated',
                                'parent_name' => $parent_status->name,
                                'old_mobile' => $parent_status->mobile_no,
                                'new_mobile' => $parent_mobile,
                                'matched_by' => 'email'
                            ];
                            $parent_status->mobile_no = $parent_mobile;
                            $parent_status->save();
                        }
                    }
                }

                if ($parent_status === null)
                {
                    // Parent doesn't exist - create new parent
                    $parent->parent            = 'add';
                    $parent->firstname         = trim($row['parent_firstname'] ?? '');
                    $parent->lastname          = trim($row['parent_lastname'] ?? '');
                    
                    // Set parent name (required by CreateParent)
                    $parent->name              = trim($parent->firstname . ' ' . $parent->lastname);
                    
                    $parent->mobile_no         = $parent_mobile;
                    $parent->alternate_no      = $row['parent_alternate_no'];
                    $parent->qualification_id  = $qualification_id;
                    $parent->email             = $parent_email;
                    $parent->profession        = $row['parent_occupation'];
                    $parent->designation       = $row['parent_designation'];
                    $parent->sub_occupation    = $row['parent_sub_occupation'];
                    $parent->organization_name = $row['parent_organization_name'];
                    $parent->official_address  = $row['parent_official_address'];
                    $parent->annual_income     = $row['parent_annual_income'];
                    $parent->relation          = $row['relation'];
                    
                    // Track new parent creation
                    $this->parentsCreated++;
                }
                else
                {
                    // Parent already exists - reuse (sibling scenario)
                    $parent->parent    = 'select';
                    $parent->select_id = $parent_status->id;
                    
                    // Track sibling linked to existing parent
                    $this->siblingsLinked++;
                }

                $avatar = '';

                $student = $this->CreateUser($student, $school_id, $academic_year->id, $avatar, 6);
                $this->CreateParent($student->id, $parent, $school_id, 7);

                $insertedcount++;
            }

            \Session::put('insertedcount', $insertedcount);
            \Session::put('parents_created', $this->parentsCreated);
            \Session::put('siblings_linked', $this->siblingsLinked);
        }
        catch (Exception $e)
        {
            Log::error("UsersImport Error: " . $e->getMessage() . " at line " . $e->getLine());
            throw $e; // Re-throw so the controller can handle it
        }
    }
}
