<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Add admin role to users table, keeping others intact
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'project_manager', 'team_member', 'client') NOT NULL DEFAULT 'team_member'");

            // Update tasks.review_stage from pm_review to team_review
            // Step 1. Add both to enum temporarily to prevent precision/truncation error
            DB::statement("ALTER TABLE tasks MODIFY COLUMN review_stage ENUM('none', 'pm_review', 'team_review', 'client_review') NOT NULL DEFAULT 'none'");
            
            // Step 2. Convert any existing 'pm_review' to 'team_review'
            DB::statement("UPDATE tasks SET review_stage = 'team_review' WHERE review_stage = 'pm_review'");

            // Step 3. Drop pm_review from enum completely
            DB::statement("ALTER TABLE tasks MODIFY COLUMN review_stage ENUM('none', 'team_review', 'client_review') NOT NULL DEFAULT 'none'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Revert role enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'project_manager', 'team_member', 'client') NOT NULL DEFAULT 'team_member'");

            // Revert review stage enum
            DB::statement("ALTER TABLE tasks MODIFY COLUMN review_stage ENUM('none', 'pm_review', 'team_review', 'client_review') NOT NULL DEFAULT 'none'");
            DB::statement("UPDATE tasks SET review_stage = 'pm_review' WHERE review_stage = 'team_review'");
            DB::statement("ALTER TABLE tasks MODIFY COLUMN review_stage ENUM('none', 'pm_review', 'client_review') NOT NULL DEFAULT 'none'");
        }
    }
};
