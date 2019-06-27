<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetrackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('timetracking_id');
            $table->string('employee')->references('employee_id')->on('employees');
            $table->string('started_on');
            $table->string('started_at');
            $table->string('ended_at');
            $table->integer('duration');
            $table->text('description');
            $table->string('milestone')->references('milestone_id')->on('milestones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetrackings');
    }
}
