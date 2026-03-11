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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('project_manager_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'completed', 'on_hold', 'cancelled'])->default('planning');
            $table->enum('visibility', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('show_team_to_client')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('client_id');
            $table->index('project_manager_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
