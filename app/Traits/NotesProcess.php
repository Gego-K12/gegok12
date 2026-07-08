<?php

namespace App\Traits;

use App\Models\Notes;

trait NotesProcess
{
    /**
     * Create a note entry for a given entity.
     *
     * @param  string  $note  Note content
     * @param  int  $school_id  School identifier
     * @param  int  $entity_id  Related entity identifier
     * @param  string  $entity_name  Fully qualified model name of the related entity
     * @param  int  $created_by  User ID creating the note
     * @param  int  $updated_by  User ID updating the note
     * @return Notes Persisted note model
     */
    public function createNotes($note, $school_id, $entity_id, $entity_name, $created_by, $updated_by)
    {

        $notes = new Notes;
        $notes->notes = $note;
        $notes->school_id = $school_id;
        $notes->entity_id = $entity_id;
        $notes->entity_name = $entity_name;
        $notes->created_by = $created_by;
        $notes->updated_by = $updated_by;
        $notes->save();

        return $notes;
    }
}
