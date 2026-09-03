<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->string('validation_type')->default('none')->after('is_required');
            $table->string('validation_pattern')->nullable()->after('validation_type');
        });
    }

    public function down(): void
    {
        Schema::table('custom_field_entity_config', function (Blueprint $table) {
            $table->dropColumn(['validation_type', 'validation_pattern']);
        });
    }
};
