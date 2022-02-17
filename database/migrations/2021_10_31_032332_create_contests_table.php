<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('contest_title', 505);
            $table->string('contest_description', 10000);
            $table->string('slug', 505);
            $table->string('contest_image', 505)->nullable();
            $table->float('contest_prize');
            $table->string('start_date');
            $table->string('end_date');
            $table->bigInteger('posted_by')->unsigned()->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contests');
    }
}
