<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('found_files', function(Blueprint $table) {
           $table->id();
           $table->string('short_hash', 40)->index('short_hash');
           $table->string('long_hash', 40)->unique('long_hash');
           $table->string('path')->unique('path');
           $table->string('mime', 40);
           $table->integer('size');
           $table->dateTime('creation');
           $table->dateTime('modification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('found_files');
    }
};
