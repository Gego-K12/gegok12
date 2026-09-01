<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_entity_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->cascadeOnDelete();
            $table->enum('entity_type', ['student', 'teacher', 'staff', 'parent', 'admission']);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_required')->default(false);
            $table->integer('order_no')->default(0);
            $table->timestamps();
            $table->unique(['custom_field_id', 'entity_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_entity_config');
    }
};
