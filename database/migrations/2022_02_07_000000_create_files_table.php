<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('hash',40)->unique()->index('idx_hash');
            $table->string('ext',10);
            $table->bigInteger('size');
            $table->integer('mime');
            $table->dateTime('cdate');
            $table->dateTime('mdate');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
