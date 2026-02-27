<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowOperationsTable extends Migration
{
    public function up()
    {
        Schema::create('workflow_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('stage'); // design, manufacturing, prepare, review, shipment
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['user_id', 'stage']);
            $table->index(['shift_id', 'stage']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('shift_id')->references('id')->on('employee_shifts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('workflow_operations');
    }
}

