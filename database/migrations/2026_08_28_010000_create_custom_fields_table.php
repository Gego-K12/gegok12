<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('field_name');
            $table->string('label');
            $table->enum('field_type', ['text', 'textarea', 'number', 'email', 'date', 'select', 'radio', 'checkbox', 'file']);
            $table->boolean('status')->default(true);
            $table->integer('order_no')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['school_id', 'field_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
