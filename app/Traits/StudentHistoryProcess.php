<?php

/**
 * Trait for processing common
 */

namespace App\Traits;

use App\Models\StudentHistory;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * @class trait
 * Trait for Common Processes
 */
trait StudentHistoryProcess
{
    /**
     * Record that a parent has read an entity related to a student.
     *
     * @param  int  $school_id  School identifier
     * @param  int  $student_id  Student identifier
     * @param  int  $parent_id  Parent identifier
     * @param  int  $entity_id  Related entity identifier
     * @param  string  $entity_name  Entity model name
     * @param  string  $type  Type of history entry
     * @return void
     */
    public static function createReadHistory($school_id, $student_id, $parent_id, $entity_id, $entity_name, $type)
    {
        try {

            $exists = StudentHistory::where([['school_id', $school_id], ['student_id', $student_id], ['entity_id', $entity_id], ['type', $type]])->exists();
            if (! $exists) {
                $create = [
                    'school_id' => $school_id,
                    'student_id' => $student_id,
                    'parent_id' => $parent_id,
                    'entity_id' => $entity_id,
                    'entity_type' => $entity_name,
                    'type' => $type,
                    'read_at' => Carbon::now(),
                ];
                StudentHistory::create($create);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Count read history entries for an entity.
     *
     * @param  int  $school_id  School identifier
     * @param  int  $entity_id  Related entity identifier
     * @param  string  $entity_name  Entity name to filter on
     * @return int Number of read records
     */
    public static function getReadCount($school_id, $entity_id, $entity_name)
    {
        $count = StudentHistory::where([['school_id', $school_id], ['entity_id', $entity_id], ['entity_name', $entity_name]])->whereNotNull('read_at')->count();

        return $count;
    }
}
