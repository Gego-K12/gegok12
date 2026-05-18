<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admission_change_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admission_id');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->unsignedInteger('changed_by');
            $table->timestamp('changed_at');

            $table->foreign('admission_id')->references('id')->on('admissions')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admission_change_logs');
    }
};
