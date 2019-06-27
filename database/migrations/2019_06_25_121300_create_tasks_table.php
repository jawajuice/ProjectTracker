<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('task_id');
            $table->text('description');
            $table->boolean('completed');
            $table->string('completed_at')->nullable();
            $table->string('est_duration');
            $table->string('milestone')->references('milestone_id')->on('milestones');
            $table->string('asignee')->references('employee_id')->on('employees');
            $table->string('due_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
