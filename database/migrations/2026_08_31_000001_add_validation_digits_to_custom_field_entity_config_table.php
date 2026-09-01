<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('validation_digits')->nullable()->after('validation_pattern');
        });
    }

    public function down(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->dropColumn('validation_digits');
        });
    }
};
