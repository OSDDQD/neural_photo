<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ext');
            $table->string('path');
            $table->string('relative_path');
            $table->string('name');
            $table->string('rendered');
            $table->integer('style');
            $table->integer('size');
            $table->timestamp('generate_time');
            $table->boolean('colors')->default(false);
            $table->string('type');
            $table->boolean('is_done')->default(false);
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
        Schema::drop('image');
    }
}
