<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'event', 'admission']);
            $table->unsignedBigInteger('entity_id');
            $table->text('value')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->unique(['custom_field_id', 'entity_type', 'entity_id'], 'custom_field_values_unique');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
    }
};
