<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['milestone_id']);
            $table->foreign('milestone_id')->references('id')->on('milestones')->cascadeOnDelete();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();

            $table->dropForeign(['milestone_id']);
            $table->foreign('milestone_id')->references('id')->on('milestones')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['milestone_id']);
            $table->foreign('milestone_id')->references('id')->on('milestones')->nullOnDelete();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->foreign('task_id')->references('id')->on('tasks')->nullOnDelete();

            $table->dropForeign(['milestone_id']);
            $table->foreign('milestone_id')->references('id')->on('milestones')->nullOnDelete();
        });
    }
};
