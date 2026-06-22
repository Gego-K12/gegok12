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
            // Fresh objects every row - this is what was missing and caused
            // "Attempt to assign property on null"
            $student = new \stdClass();
            $parent  = new \stdClass();

            // Reset per-row scratch arrays so previous row's data can't leak in
            $arr                 = [];
            $qualification_array = [];
            $qualification_id    = [];

            try
            {
                // --- Qualifications -------------------------------------------------
                $qualArray = str_getcsv((string) $row['parent_qualification']);

                foreach ($qualArray as $i => $qualName)
                {
                    $ids = Qualification::whereIn('type', ['ug', 'pg'])
                        ->where('display_name', 'LIKE', '%' . $qualName . '%')
                        ->pluck('id')
                        ->toArray();

                    $arr[$i]                 = $ids;
                    $qualification_array[$i] = implode('', $ids);
                    $qualification_id[$i]     = $qualification_array[$i] === '' ? 1 : $qualification_array[$i];
                }

                // --- Class / standard resolution
                if (is_int($row['class']))
                {
                    $class_name = $row['class'];
                }
                else
                {
                    if (in_array($row['class'], ['prekg', 'lkg', 'ukg'], true))
                    {
                        $class_name = $row['class'];
                    }
                    else
                    {
                        $class_name = $this->romanToInteger($row['class']);
                    }
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
                        'row'         => $rowIndex + 2, // +2 to account for heading row + 0-index
                        'class'       => $row['class'] ?? null,
                        'section'     => $row['section'] ?? null,
                        'firstname'   => $row['firstname'] ?? null,
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
                $student->firstname           = $row['firstname'];
                $student->lastname            = $row['lastname'];
                $student->mobile_no           = $row['mobile_no'];
                $student->email               = empty($row['email']) ? null : strtolower($row['email']);
                $student->gender              = strtolower((string) $row['gender']);
                $student->date_of_birth       = date('Y-m-d', strtotime($row['date_of_birth']));
                $student->blood_group         = empty($row['blood_group'])
                    ? null
                    : str_replace('ve', '', strtolower($row['blood_group']));
                $student->standard            = $standardLink->id;
                $student->address             = $row['address'];
                $student->city_id             = $city->id ?? null;
                $student->state_id            = $state->id ?? null;
                $student->country_id          = $country->id ?? null;
                $student->pincode             = $row['pincode'];
                $student->birth_place         = $row['birth_place'];
                $student->native_place        = $row['native_place'];
                $student->mother_tongue       = $row['mother_tongue'];
                $student->caste                = $row['caste'];
                $student->sub_caste            = $row['sub_caste'];
                $student->aadhar_number        = $row['aadhar_number'];
                $student->joining_date         = date('Y-m-d', strtotime($row['joining_date']));
                $student->registration_number  = $row['admission_number'];
                $student->EMIS_number           = $row['emis_number'];
                $student->roll_number           = $row['roll_number'];
                $student->id_card_number        = $row['id_card_number'];

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

                // --- Parent resolution / build ---------------------------------------
                $parent_status = User::where([
                    ['school_id', $school_id],
                    ['mobile_no', $row['parent_mobile_no']],
                    ['usergroup_id', 7],
                ])->first();

                if (! $parent_status)
                {
                    $parent->parent            = 'add';
                    $parent->firstname         = $row['parent_firstname'];
                    $parent->lastname          = $row['parent_lastname'];
                    $parent->mobile_no         = $row['parent_mobile_no'];
                    $parent->alternate_no      = $row['parent_alternate_no'];
                    $parent->qualification_id  = $qualification_id;
                    $parent->email             = $row['parent_email'];
                    $parent->profession        = $row['parent_occupation'];
                    $parent->designation       = $row['parent_designation'];
                    $parent->sub_occupation    = $row['parent_sub_occupation'];
                    $parent->organization_name = $row['parent_organization_name'];
                    $parent->official_address  = $row['parent_official_address'];
                    $parent->annual_income     = $row['parent_annual_income'];
                    $parent->relation          = $row['relation'];
                }
                else
                {
                    $parent->parent    = 'select';
                    $parent->select_id = $parent_status->id;
                }

                $avatar = '';

                $student = $this->CreateUser($student, $school_id, $academic_year->id, $avatar, 6);
                $this->CreateParent($student->id, $parent, $school_id, 7);

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
}