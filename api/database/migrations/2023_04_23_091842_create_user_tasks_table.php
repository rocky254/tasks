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
        Schema::create('user_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->timestamp('due_date')->useCurrent();
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->useCurrent();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
            $table->softDeletes();
            /**
             * Foreign Keys
             */
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tasks');
    }
};
