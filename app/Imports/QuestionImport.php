<?php

namespace App\Imports;

use Gegok12\Quiz\Models\QuizQuestion;
use Gegok12\Quiz\Models\QuizOption;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class QuestionImport
 *
 * Handles importing quiz questions and options from an Excel file.
 * Each row represents a question with up to four options.
 *
 * The import process:
 * - Creates quiz questions
 * - Creates related options
 * - Wraps operations in a database transaction
 */
class QuestionImport implements ToCollection, WithHeadingRow
{
    /**
     * Chapter identifier for the imported questions.
     *
     * @var int|string
     */
    public $chapter_id;

    /**
     * Head identifier for the imported questions.
     *
     * @var int|string
     */
    public $head_id;

    /**
     * Create a new QuestionImport instance.
     *
     * @param  int|string  $chapter_id  Chapter identifier
     * @param  int|string  $head_id  Head identifier
     */
    public function __construct($chapter_id, $head_id)
    {
        $this->chapter_id = $chapter_id;
        $this->head_id = $head_id;
    }

    /**
     * Process the imported Excel rows.
     *
     * For each row:
     * - A quiz question is created
     * - Related quiz options are created if present
     *
     * All database operations are wrapped in a transaction
     * to ensure data integrity.
     *
     * @return void
     */
    public function collection(Collection $collection)
    {
        \DB::beginTransaction();
        try {
            foreach ($collection as $row) {
                $question = new QuizQuestion;
                $question->chapter_id = $this->chapter_id;
                $question->head_id = $this->head_id;
                $question->question = $row['question'];
                $question->type = $row['type'];
                $question->page_no = $row['page_no'];
                $question->created_by = Auth::id();
                $question->save();

                $options = [
                    'option1' => $row['option1'],
                    'option2' => $row['option2'],
                    'option3' => $row['option3'],
                    'option4' => $row['option4'],
                ];

                foreach ($options as $key => $option_value) {
                    if ($option_value != null && $option_value != '') {
                        $option = new QuizOption;
                        $option->option = $option_value;
                        $option->is_answer = 0;
                        $option->question_id = $question->id;
                        $option->save();
                    }
                }

                $insertedcount++;
            }

            \DB::commit();
            \Session::put('questioncount', $insertedcount);
        } catch (Exception $e) {
            \DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
