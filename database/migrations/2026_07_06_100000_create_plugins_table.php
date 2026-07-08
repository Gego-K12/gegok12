<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('version')->nullable();
            $table->enum('source_type', ['git', 'zip']);
            $table->text('source_ref');
            $table->string('composer_package');
            $table->string('provider_class');
            $table->string('portal');
            $table->enum('status', ['staged', 'installing', 'installed', 'failed'])->default('staged');
            $table->longText('log')->nullable();
            $table->integer('requested_by')->unsigned();
            $table->foreign('requested_by')->references('id')->on('users');
            $table->timestamp('installed_at')->nullable();
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
        Schema::dropIfExists('plugins');
    }
}
