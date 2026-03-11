<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Change project_members.role from ENUM('project_manager','team_member')
     * to VARCHAR to support all roles including 'client'.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('project_members', function (Blueprint $table) {
            $table->string('role')->default('team_member')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('project_members', function (Blueprint $table) {
            $table->enum('role', ['project_manager', 'team_member'])->default('team_member')->change();
        });
    }
};
