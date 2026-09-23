<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * custom_field_entity_config.entity_type was left as
 * ENUM('student','teacher','staff','parent','admission') -- the sibling
 * 2026_09_03_000000_add_event_to_custom_field_values_entity_type migration
 * widened custom_field_values instead of this table, despite its own
 * docstring claiming this table's enum already included 'event'. Saving a
 * custom field with the "Event" entity enabled therefore made MySQL
 * (outside strict mode) silently truncate 'event' to '', which then
 * collided with itself on every later save via the
 * custom_field_entity_config_custom_field_id_entity_type_unique index.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'admission', 'event'])->change();
        });

        // Recover the row(s) that were already silently corrupted to '' by
        // the enum mismatch -- 'event' is the only entity type missing from
        // the pre-fix enum, so any blank value can only have come from it.
        DB::table('custom_field_entity_config')
            ->where('entity_type', '')
            ->update(['entity_type' => 'event']);
    }

    public function down(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'admission'])->change();
        });
    }
};
