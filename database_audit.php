<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$output = [];
$queries = [];

// Step 4: Find invalid client users (role = client and client_id IS NULL)
$invalidClients = \App\Models\User::where('role', 'client')->whereNull('client_id')->get();

if ($invalidClients->isNotEmpty()) {
    $output[] = "Found " . $invalidClients->count() . " client users with NULL client_id.";
    
    // Check if we have a default client company
    $clientCompany = \App\Models\Client::first();
    if (!$clientCompany) {
        $queries[] = "-- No client company found, creating one";
        $clientCompany = \App\Models\Client::create([
            'name' => 'Default Client',
            'company_name' => 'Default Company',
            'email' => 'client@example.com',
        ]);
        $queries[] = "INSERT INTO clients (name, company_name, email) VALUES ('Default Client', 'Default Company', 'client@example.com');";
        $output[] = "Created default client company ID: " . $clientCompany->id;
    }

    foreach ($invalidClients as $user) {
        // Fix: Assign client_id
        $user->update(['client_id' => $clientCompany->id]);
        $queries[] = "UPDATE users SET client_id = {$clientCompany->id} WHERE id = {$user->id};";
    }
} else {
    $output[] = "No client users with NULL client_id found.";
}

// Step 6: Validate Project Relationships
$invalidProjects = \App\Models\Project::whereNull('client_id')->get();
if ($invalidProjects->isNotEmpty()) {
    $output[] = "Found " . $invalidProjects->count() . " projects without a client_id.";
} else {
    $output[] = "All projects have a valid client_id.";
}

$invalidPMs = \App\Models\Project::whereNull('project_manager_id')->orWhereNotIn('project_manager_id', function ($q) {
    $q->select('id')->from('users')->whereIn('role', ['super_admin', 'admin', 'project_manager']);
})->get();
if ($invalidPMs->isNotEmpty()) {
    $output[] = "Found " . $invalidPMs->count() . " projects with invalid project_manager_id (must be an internal user with sufficient permissions).";
} else {
    $output[] = "All projects have a valid project_manager_id.";
}

$invalidTaskProjects = \App\Models\Task::whereNull('project_id')->get();
if ($invalidTaskProjects->isNotEmpty()) {
    $output[] = "Found " . $invalidTaskProjects->count() . " tasks without a project_id.";
} else {
    $output[] = "All tasks have a valid project_id.";
}

$invalidTaskAssignees = \App\Models\Task::whereNotNull('assigned_to')->whereNotIn('assigned_to', function ($q) {
    $q->select('id')->from('users');
})->get();
if ($invalidTaskAssignees->isNotEmpty()) {
    $output[] = "Found " . $invalidTaskAssignees->count() . " tasks with an invalid assigned_to reference.";
} else {
    $output[] = "All assigned tasks have a valid assigned_to reference.";
}


// Step 4 continued: Ensure internal roles don't have client_id
$invalidInternal = \App\Models\User::whereIn('role', ['super_admin', 'admin', 'project_manager', 'team_member'])->whereNotNull('client_id')->get();
if ($invalidInternal->isNotEmpty()) {
    $output[] = "Found " . $invalidInternal->count() . " internal users with a non-null client_id.";
    foreach ($invalidInternal as $user) {
        $user->update(['client_id' => null]);
        $queries[] = "UPDATE users SET client_id = NULL WHERE id = {$user->id};";
    }
} else {
    $output[] = "All internal users have NULL client_id.";
}

// Step 7: Validate Role Structure
$invalidRoles = \App\Models\User::whereNotIn('role', ['super_admin', 'admin', 'project_manager', 'team_member', 'client'])->get();
if ($invalidRoles->isNotEmpty()) {
    $output[] = "Found " . $invalidRoles->count() . " users with an invalid role.";
} else {
    $output[] = "All users have a valid role.";
}

echo json_encode([
    'output' => $output,
    'queries' => $queries
], JSON_PRETTY_PRINT);
