<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SchoolDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->string('meta_key')->nullable();
            $table->LongText('meta_value')->nullable();

            /* $table->text('moto')->nullable();
             $table->string('affiliated_by')->nullable();
             $table->string('affiliation_no')->nullable();
             $table->dateTime('date_of_establishment');
             $table->string('board');
             $table->text('school_logo')->nullable();
             $table->string('landline_no');
             $table->LongText('about_us');*/

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('church_details');
    }
}
