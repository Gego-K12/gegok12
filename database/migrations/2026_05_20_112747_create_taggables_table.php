<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');

            $table->morphs('taggable');

            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');

            $table->unique(
                ['tag_id', 'taggable_id', 'taggable_type'],
                'taggables_tag_id_taggable_id_taggable_type_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};