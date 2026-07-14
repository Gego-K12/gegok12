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
 * NOTE: StudentFormatExport now exports separate father_*, mother_*, and
 * guardian_* columns (instead of a single parent_* block with a "relation"
 * column). This class was updated to match: father, mother, and guardian
 * are each resolved/created as their own parent user (usergroup_id 7),
 * using the same full field set (qualification, email, occupation, etc.).
 *
 * @package App\Imports
 */
class UsersImport implements ToCollection, WithHeadingRow
{
    use RegisterUser;
    use Common;

    /**
     * Process the imported Excel rows.
     *
     * For each row:
     * - Resolves academic year, standard, section
     * - Creates student user
     * - Creates or links father, mother, and/or guardian user
     * - Handles qualifications, siblings, transport info
     *
     * Insert count is stored in session.
     *
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $school_id     = Auth::user()->school_id;
        $academic_year = AcademicYear::where('school_id', $school_id)->where('status', 1)->first();
        $user_count    = User::ByRole(6)->where('school_id', $school_id)->count();
        $subscription  = Subscription::where('school_id', $school_id)->first();
        $count         = $subscription && $subscription->plan
            ? $subscription->plan->no_of_members - $user_count
            : 0;

        $insertedcount = 0;
        $skipped_rows  = [];

        foreach ($rows as $rowIndex => $row)
        {
            // Fresh objects every row - keeps "Attempt to assign property on null"
            // and cross-row data leakage from coming back.
            $student = new \stdClass();
            $father  = new \stdClass();
            $mother  = new \stdClass();
            $guardian = new \stdClass();

            try
            {
                // --- Class / standard resolution
                $class = trim((string) $row['class']);


                if (is_numeric($class)) {
                    $class_name = (int) $class;
                    $roman = $this->romanToInteger(strtoupper($class));
                    $class_name = $roman;
                }
                 else {
                    // Keep the original class name
                    $class_name = $class;
                    }

                $country = Country::where('name', 'LIKE', '%' . $row['country'] . '%')->first();
                $state   = State::where('name', 'LIKE', '%' . $row['state'] . '%')->first();
                $city    = City::where('name', 'LIKE', '%' . $row['city'] . '%')->first();

                $standard = Standard::where([
                    ['school_id', $school_id],
                    ['name', 'LIKE', $class_name],
                ])->first();

                $section = Section::where([
                    ['school_id', $school_id],
                    ['name', 'LIKE', $row['section']],
                ])->first();

                // Guard: can't build a standard link without standard + section
                if (! $standard || ! $section)
                {
                    Log::warning('UsersImport: skipping row, standard/section not found', [
                        'row'       => $rowIndex + 2, // +2 to account for heading row + 0-index
                        'class'     => $row['class'] ?? null,
                        'section'   => $row['section'] ?? null,
                        'firstname' => $row['firstname'] ?? null,
                    ]);
                    $skipped_rows[] = $rowIndex + 2;
                    continue;
                }

                $standardLink = StandardLink::where([
                    ['school_id', $school_id],
                    ['standard_id', $standard->id],
                    ['section_id', $section->id],
                ])->first();

                if (! $standardLink)
                {
                    Log::warning('UsersImport: skipping row, standard link not found', [
                        'row'         => $rowIndex + 2,
                        'standard_id' => $standard->id,
                        'section_id'  => $section->id,
                    ]);
                    $skipped_rows[] = $rowIndex + 2;
                    continue;
                }

                // --- Build student object
                $student->firstname     = $row['firstname'];
                $student->lastname      = $row['lastname'];
                $student->mobile_no     = $row['mobile_no'];
                $student->email         = empty($row['email']) ? null : strtolower($row['email']);
                $student->gender        = strtolower((string) $row['gender']);
                $student->date_of_birth = date('Y-m-d', strtotime($row['date_of_birth']));
                $student->blood_group   = empty($row['blood_group'])
                    ? null
                    : str_replace('ve', '', strtolower($row['blood_group']));
                $student->standard             = $standardLink->id;
                $student->address              = $row['address'];
                $student->city_id              = $city->id ?? null;
                $student->state_id             = $state->id ?? null;
                $student->country_id           = $country->id ?? null;
                $student->pincode              = $row['pincode'];
                $student->birth_place          = $row['birth_place'];
                $student->native_place         = $row['native_place'];
                $student->mother_tongue        = $row['mother_tongue'];
                $student->caste                = $row['caste'];
                $student->sub_caste            = $row['sub_caste'];
                $student->aadhar_number        = $row['aadhar_number'];
                $student->joining_date         = date('Y-m-d', strtotime($row['joining_date']));
                $student->registration_number  = $row['admission_number'];
                $student->EMIS_number          = $row['emis_number'];
                $student->roll_number          = $row['roll_number'];
                $student->id_card_number       = $row['id_card_number'];

                if (in_array($class_name, [10, 12], true))
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
                $student->sibling_relation      = str_getcsv((string) $row['sibling_relation']);
                $student->sibling_name          = str_getcsv((string) $row['sibling_name']);
                $student->sibling_date_of_birth = str_getcsv((string) $row['sibling_date_of_birth']);
                $student->sibling_standard      = str_getcsv((string) $row['sibling_class']);
                $student->notes                 = $row['notes'];

                // --- Guardian resolution / build -------------------------------------
                $hasGuardian = ! empty($row['guardian_mobile_no']) || ! empty($row['guardian_firstname']);

                if ($hasGuardian)
                {
                    $guardian_status = User::where([
                        ['school_id', $school_id],
                        ['mobile_no', $row['guardian_mobile_no']],
                        ['usergroup_id', 7],
                    ])->first();

                    if (! $guardian_status)
                    {
                        $guardian->parent            = 'add';
                        $guardian->firstname         = $row['guardian_firstname'];
                        $guardian->lastname          = $row['guardian_lastname'];
                        $guardian->mobile_no         = $row['guardian_mobile_no'];
                        $guardian->alternate_no      = $row['guardian_alternate_no'];
                        $guardian->qualification_id  = $this->resolveQualificationIds($row['guardian_qualification']);
                        $guardian->email             = $row['guardian_email'];
                        $guardian->profession        = $row['guardian_occupation'];
                        $guardian->designation       = $row['guardian_designation'];
                        $guardian->sub_occupation    = $row['guardian_sub_occupation'];
                        $guardian->organization_name = $row['guardian_organization_name'];
                        $guardian->official_address  = $row['guardian_official_address'];
                        $guardian->annual_income     = $row['guardian_annual_income'];
                        $guardian->relation          = 'guardian';
                    }
                    else
                    {
                        $guardian->parent    = 'select';
                        $guardian->select_id = $guardian_status->id;
                    }
                }

                // --- Father resolution / build ---------------------------------------
                $hasFather = ! empty($row['father_mobile_no']) || ! empty($row['father_firstname']);

                if ($hasFather)
                {
                    $father_status = User::where([
                        ['school_id', $school_id],
                        ['mobile_no', $row['father_mobile_no']],
                        ['usergroup_id', 7],
                    ])->first();

                    if (! $father_status)
                    {
                        $father->parent            = 'add';
                        $father->firstname         = $row['father_firstname'];
                        $father->lastname          = $row['father_lastname'];
                        $father->mobile_no         = $row['father_mobile_no'];
                        $father->alternate_no      = $row['father_alternate_no'];
                        $father->qualification_id  = $this->resolveQualificationIds($row['father_qualification']);
                        $father->email             = $row['father_email'];
                        $father->profession        = $row['father_occupation'];
                        $father->designation       = $row['father_designation'];
                        $father->sub_occupation    = $row['father_sub_occupation'];
                        $father->organization_name = $row['father_organization_name'];
                        $father->official_address  = $row['father_official_address'];
                        $father->annual_income     = $row['father_annual_income'];
                        $father->relation          = 'father';
                    }
                    else
                    {
                        $father->parent    = 'select';
                        $father->select_id = $father_status->id;
                    }
                }

                // --- Mother resolution / build ---------------------------------------
                $hasMother = ! empty($row['mother_mobile_no']) || ! empty($row['mother_firstname']);

                if ($hasMother)
                {
                    $mother_status = User::where([
                        ['school_id', $school_id],
                        ['mobile_no', $row['mother_mobile_no']],
                        ['usergroup_id', 7],
                    ])->first();

                    if (! $mother_status)
                    {
                        $mother->parent            = 'add';
                        $mother->firstname         = $row['mother_firstname'];
                        $mother->lastname          = $row['mother_lastname'];
                        $mother->mobile_no         = $row['mother_mobile_no'];
                        $mother->alternate_no      = $row['mother_alternate_no'];
                        $mother->qualification_id  = $this->resolveQualificationIds($row['mother_qualification']);
                        $mother->email             = $row['mother_email'];
                        $mother->profession        = $row['mother_occupation'];
                        $mother->designation       = $row['mother_designation'];
                        $mother->sub_occupation    = $row['mother_sub_occupation'];
                        $mother->organization_name = $row['mother_organization_name'];
                        $mother->official_address  = $row['mother_official_address'];
                        $mother->annual_income     = $row['mother_annual_income'];
                        $mother->relation          = 'mother';
                    }
                    else
                    {
                        $mother->parent    = 'select';
                        $mother->select_id = $mother_status->id;
                    }
                }

                $avatar = '';

                $student = $this->CreateUser($student, $school_id, $academic_year->id, $avatar, 6);

                if ($hasFather)
                {
                    $this->CreateParent($student->id, $father, $school_id, 7);
                }

                if ($hasMother)
                {
                    $this->CreateParent($student->id, $mother, $school_id, 7);
                }

                if ($hasGuardian)
                {
                    $this->CreateParent($student->id, $guardian, $school_id, 7);
                }

                $insertedcount++;
            }
            catch (Exception $e)
            {
                // Log full context + trace instead of just the message,
                // and continue with the next row instead of aborting the whole import.
                Log::error('UsersImport: failed to import row', [
                    'row'       => $rowIndex + 2,
                    'firstname' => $row['firstname'] ?? null,
                    'message'   => $e->getMessage(),
                    'trace'     => $e->getTraceAsString(),
                ]);
                $skipped_rows[] = $rowIndex + 2;
                continue;
            }
        }

        \Session::put('insertedcount', $insertedcount);
        \Session::put('skipped_rows', $skipped_rows);
    }

    /**
     * Parse a comma-separated qualification string (e.g. "UG Degree,PG Degree")
     * into an array of resolved qualification IDs, falling back to 1 for any
     * entry that doesn't match a known qualification.
     *
     * @param string|null $qualificationCsv
     * @return array
     */
    private function resolveQualificationIds(?string $qualificationCsv): array
    {
        $qualArray         = str_getcsv((string) $qualificationCsv);
        $qualification_id  = [];

        foreach ($qualArray as $i => $qualName)
        {
            $qualName = trim((string) $qualName);

            if ($qualName === '')
            {
                continue;
            }

            $ids = Qualification::whereIn('type', ['ug', 'pg'])
                ->where('display_name', 'LIKE', '%' . $qualName . '%')
                ->pluck('id')
                ->toArray();

            $qualification_id[$i] = empty($ids) ? 1 : implode('', $ids);
        }

        return $qualification_id;
    }
}