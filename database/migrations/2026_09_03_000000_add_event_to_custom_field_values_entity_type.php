<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The custom_field_values migration's ENUM list already included 'event'
 * in its source, but only after it had already run against existing
 * databases -- editing an already-applied migration file doesn't change
 * the live schema. custom_field_entity_config's enum does include 'event'
 * (added the same way, just not yet run into this problem), so admins
 * could already mark a custom field enabled for the "event" entity type
 * via the Custom Fields manager, but saving an actual value for it would
 * silently corrupt: MySQL (outside strict mode) truncates an out-of-enum
 * value to '', which then collides with any other event custom field
 * value on the unique (custom_field_id, entity_type, entity_id) index.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_field_values', function (Blueprint $table) {
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'admission', 'event'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('custom_field_values', function (Blueprint $table) {
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'admission'])->change();
        });
    }
};
