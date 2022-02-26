<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 505);
            $table->string('details', 10000);
            $table->string('summary', 505);
            $table->string('slug', 1000);
            $table->string('story_image', 1000)->nullable();
            $table->boolean('adult')->default(0);
            $table->boolean('stories_status')->default(0);
            $table->bigInteger('contest_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('added_by')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade'); 
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); 
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stories');
    }
}
