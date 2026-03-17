<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'app:database-backup';
    protected $description = 'Create a compressed database backup and clean up old backups';

    public function handle(): int
    {
        $backupDir = storage_path('app/backups');

        // Ensure backup directory exists
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql.gz';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        $this->info("Starting database backup: {$filename}");

        $success = $this->runMysqldump($filePath);

        if (!$success) {
            $this->warn('mysqldump not available or failed. Falling back to PHP-based backup...');
            $success = $this->runPhpBackup($filePath);
        }

        if (!$success) {
            $this->error(__('app.backup_failed'));
            return self::FAILURE;
        }

        $this->info(__('app.backup_created') . ' → ' . $filePath);

        // Delete backups older than 7 days
        $this->cleanOldBackups($backupDir);

        return self::SUCCESS;
    }

    private function runMysqldump(string $filePath): bool
    {
        $db = config('database.connections.mysql');

        if (empty($db['host']) || empty($db['database'])) {
            return false;
        }

        $host     = escapeshellarg($db['host']);
        $port     = !empty($db['port']) ? (int) $db['port'] : 3306;
        $username = escapeshellarg($db['username']);
        $password = $db['password'] ?? '';
        $database = escapeshellarg($db['database']);
        $output   = escapeshellarg($filePath);

        $passwordArg = $password !== ''
            ? '-p' . escapeshellarg($password)
            : '';

        $cmd = "mysqldump --host={$host} --port={$port} --user={$username} {$passwordArg} "
             . "--single-transaction --quick --lock-tables=false {$database} "
             . "| gzip > {$output} 2>&1";

        // Test if mysqldump binary exists
        exec('which mysqldump 2>/dev/null', $whichOut, $whichCode);
        if ($whichCode !== 0) {
            return false;
        }

        exec($cmd, $execOut, $exitCode);

        if ($exitCode !== 0) {
            $this->warn('mysqldump exit code: ' . $exitCode);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return false;
        }

        return file_exists($filePath) && filesize($filePath) > 0;
    }

    private function runPhpBackup(string $filePath): bool
    {
        try {
            $sqlFilePath = str_replace('.sql.gz', '.sql', $filePath);
            $handle = fopen($sqlFilePath, 'w');

            if (!$handle) {
                $this->error('Cannot open file for writing: ' . $sqlFilePath);
                return false;
            }

            $dbName = config('database.connections.mysql.database');

            fwrite($handle, "-- HospitalHub Database Backup\n");
            fwrite($handle, "-- Generated: " . now()->toDateTimeString() . "\n");
            fwrite($handle, "-- Database: {$dbName}\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;

            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey;
                $safeTable = '`' . $table . '`';

                // Write CREATE TABLE
                $createResult = DB::select("SHOW CREATE TABLE {$safeTable}");
                if (!empty($createResult)) {
                    $createSql = $createResult[0]->{'Create Table'};
                    fwrite($handle, "DROP TABLE IF EXISTS {$safeTable};\n");
                    fwrite($handle, $createSql . ";\n\n");
                }

                // Write INSERT statements in chunks
                $offset = 0;
                $chunkSize = 500;

                while (true) {
                    $rows = DB::table($table)->offset($offset)->limit($chunkSize)->get();

                    if ($rows->isEmpty()) {
                        break;
                    }

                    fwrite($handle, "INSERT INTO {$safeTable} VALUES\n");

                    $rowLines = [];
                    foreach ($rows as $row) {
                        $values = array_map(function ($val) {
                            if ($val === null) return 'NULL';
                            return "'" . addslashes((string) $val) . "'";
                        }, (array) $row);
                        $rowLines[] = '(' . implode(', ', $values) . ')';
                    }

                    fwrite($handle, implode(",\n", $rowLines) . ";\n\n");

                    $offset += $chunkSize;

                    if ($rows->count() < $chunkSize) {
                        break;
                    }
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // Compress to .sql.gz
            $gz = gzopen($filePath, 'wb9');
            if (!$gz) {
                return false;
            }

            $sqlHandle = fopen($sqlFilePath, 'rb');
            while (!feof($sqlHandle)) {
                gzwrite($gz, fread($sqlHandle, 65536));
            }
            fclose($sqlHandle);
            gzclose($gz);

            // Remove uncompressed file
            unlink($sqlFilePath);

            $this->info('PHP-based backup completed successfully.');
            return file_exists($filePath) && filesize($filePath) > 0;

        } catch (\Exception $e) {
            $this->error('PHP backup failed: ' . $e->getMessage());
            return false;
        }
    }

    private function cleanOldBackups(string $backupDir): void
    {
        $cutoff = now()->subDays(7)->getTimestamp();
        $deleted = 0;

        foreach (glob($backupDir . '/backup_*.sql.gz') as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("Deleted {$deleted} old backup(s) older than 7 days.");
        }
    }
}
