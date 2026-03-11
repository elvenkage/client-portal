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
        Schema::table('project_members', function (Blueprint $table) {
            $table->index(['project_id', 'user_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index('milestone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_members', function (Blueprint $table) {
            $table->dropIndex(['project_id', 'user_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['milestone_id']);
        });
    }
};
