<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a routine structural backup of the primary application database depending on configured connection.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = config('database.default');
        $filename = 'database_backup_' . Carbon::now()->format('Y-m-d_H-i-s');

        // Ensure backup directory exists
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        $storagePath = storage_path('app/backups/' . $filename);

        try {
            if ($connection === 'sqlite') {
                $dbPath = config('database.connections.sqlite.database');
                // For SQLite, a straightforward file copy is safest and 100% robust.
                \Illuminate\Support\Facades\File::copy($dbPath, $storagePath . '.sqlite');

                $this->info("SQLite Backup created successfully: {$storagePath}.sqlite");
            } elseif ($connection === 'mysql') {
                $host = config('database.connections.mysql.host');
                $port = config('database.connections.mysql.port');
                $username = escapeshellarg(config('database.connections.mysql.username'));
                $password = escapeshellarg(config('database.connections.mysql.password'));
                $database = escapeshellarg(config('database.connections.mysql.database'));

                $storagePath .= '.sql';

                // Using mysqldump binary assuming it is in PATH
                // Omit password argument if it is empty to prevent terminal prompt hanging
                $passArg = config('database.connections.mysql.password') ? "--password={$password}" : '';

                $command = "mysqldump --user={$username} {$passArg} --host={$host} --port={$port} {$database} > " . escapeshellarg($storagePath);

                exec($command, $output, $returnVar);

                if ($returnVar === 0) {
                    $this->info("MySQL Backup created successfully: {$storagePath}");
                } else {
                    $this->error("MySQL Backup failed. Ensure mysqldump is installed and accessible.");
                }
            } else {
                $this->error("Backup operations for driver '{$connection}' are not currently supported.");
            }
        } catch (\Exception $e) {
            $this->error("Backup failed fatally: " . $e->getMessage());
        }
    }
}
