<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Promotion;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\User;
use App\Traits\RegisterUser;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class PromotionImport
 *
 * Handles student promotion processing via Excel import.
 * Based on exam results, students are either promoted
 * to the next academic year/standard or marked as alumni.
 *
 * Uses database transactions to ensure data integrity.
 */
class PromotionImport implements ToCollection, WithHeadingRow
{
    use RegisterUser;

    /**
     * Process imported promotion records.
     *
     * For each student row:
     * - Updates academic status (pass/fail)
     * - Creates promotion records for passed students
     * - Creates next academic year StudentAcademic records
     * - Handles alumni conversion when applicable
     *
     * All operations are wrapped in a database transaction.
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        \DB::beginTransaction();
        try {
            $exam_id = \Session::get('exam_id');
            $curr_academic_year_id = \Session::get('curr_academic_year_id');
            $next_academic_year_id = \Session::get('next_academic_year_id');
            $curr_standardLink_id = \Session::get('curr_standardlink_id');
            $next_standardLink_id = \Session::get('next_standardlink_id');

            $school_id = Auth::user()->school_id;
            $insertedcount = 0;

            foreach ($rows as $row) {
                $curr_studentAcademic = StudentAcademic::where('roll_number', $row['roll_number'])->first();

                if ($curr_studentAcademic->academic_status == null) {
                    if ($next_standardLink_id != 'alumni') {
                        $curr_standardLink = StandardLink::where('id', $curr_standardLink_id)->first();
                        $next_standardLink = StandardLink::where('id', $next_standardLink_id)->first();
                        $user = User::where('id', $curr_studentAcademic->user_id)->first();

                        if ($row['academic_status'] == 'P') {
                            $academic_status = 'pass';
                        } elseif ($row['academic_status'] == 'F') {
                            $academic_status = 'fail';
                        }

                        $curr_studentAcademic->academic_status = $academic_status;
                        $curr_studentAcademic->save();

                        if ($row['academic_status'] == 'P') {
                            $promotion = new Promotion;

                            $promotion->school_id = $school_id;
                            $promotion->user_id = $user->id;
                            $promotion->current_academic_year_id = $curr_academic_year_id;
                            $promotion->current_standard_id = $curr_standardLink->standard_id;
                            $promotion->current_section_id = $curr_standardLink->section_id;
                            $promotion->exam_id = $exam_id;
                            $promotion->next_academic_year_id = $next_academic_year_id;
                            $promotion->next_standard_id = $next_standardLink->standard_id;
                            $promotion->next_section_id = $next_standardLink->section_id;
                            $promotion->comments = $row['comments'];
                            $promotion->status = 1;

                            $promotion->save();

                            $next_studentAcademic = new StudentAcademic;
                            $next_studentAcademic->school_id = $school_id;
                            $next_studentAcademic->academic_year_id = $next_academic_year_id;
                            $next_studentAcademic->user_id = $user->id;
                            $next_studentAcademic->standardLink_id = $next_standardLink->id;
                            $next_studentAcademic->roll_number = $curr_studentAcademic->roll_number;
                            $next_studentAcademic->id_card_number = $curr_studentAcademic->id_card_number;
                            $next_studentAcademic->board_registration_number = $curr_studentAcademic->board_registration_number;

                            $next_studentAcademic->save();
                        }

                        $insertedcount++;
                    } else {
                        $curr_standardLink = StandardLink::where('id', $curr_standardLink_id)->first();
                        $user = User::where('id', $curr_studentAcademic->user_id)->first();

                        if ($row['academic_status'] == 'P') {
                            $academic_status = 'pass';
                        } elseif ($row['academic_status'] == 'F') {
                            $academic_status = 'fail';
                        }

                        $curr_studentAcademic->academic_status = $academic_status;
                        $curr_studentAcademic->save();

                        if ($row['academic_status'] == 'P') {
                            $promotion = new Promotion;

                            $promotion->school_id = $school_id;
                            $promotion->user_id = $user->id;
                            $promotion->current_academic_year_id = $curr_academic_year_id;
                            $promotion->current_standard_id = $curr_standardLink->standard_id;
                            $promotion->current_section_id = $curr_standardLink->section_id;
                            $promotion->exam_id = $exam_id;
                            $promotion->next_academic_year_id = $next_academic_year_id;
                            $promotion->comments = $row['comments'];
                            $promotion->status = 1;

                            $promotion->save();

                            $curr_academic_year = AcademicYear::where('id', $curr_academic_year_id)->first();

                            $alumni_user = $this->AddAlumni(
                                $user,
                                9,
                                $school_id,
                                date('Y', strtotime($curr_academic_year->end_date))
                            );
                        }

                        $insertedcount++;
                    }
                }
            }

            \Session::put('insertedcount', $insertedcount);
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
