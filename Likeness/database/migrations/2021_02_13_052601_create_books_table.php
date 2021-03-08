<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;




/*
 *
 *
 * API for getting book data is https://www.googleapis.com/books/v1/volumes?q={{ $SearchParam }}
 *
 *
 * */
class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('api_id');
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('categories')->nullable();
            $table->text('imageLink')->nullable();
            $table->date('publishedDate')->nullable();
            $table->integer('pageCount')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('books');
    }
}
