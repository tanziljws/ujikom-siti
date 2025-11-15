<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportSqlData extends Command
{
    protected $signature = 'db:import-sql {file} {--force : Force import even if tables have data}';
    protected $description = 'Import data from SQL dump file';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        // Check database connection
        $connection = config('database.default');
        $this->info("Using database connection: {$connection}");
        
        if ($connection === 'sqlite') {
            $this->error("Error: Database masih menggunakan SQLite!");
            $this->warn("Pastikan DB_CONNECTION=mysql di Railway environment variables");
            return 1;
        }

        $this->info("Reading SQL file: {$file}");
        $sql = file_get_contents($file);
        
        // Remove comments and empty lines
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Extract INSERT statements more carefully
        preg_match_all('/INSERT INTO\s+`?(\w+)`?\s*\([^)]+\)\s*VALUES\s*\([^;]+\)/is', $sql, $matches, PREG_SET_ORDER);
        
        $statements = [];
        foreach ($matches as $match) {
            $statements[] = [
                'table' => $match[1],
                'sql' => trim($match[0]) . ';'
            ];
        }

        $this->info("Found " . count($statements) . " INSERT statements");
        
        // Group by table
        $byTable = [];
        foreach ($statements as $stmt) {
            if (!isset($byTable[$stmt['table']])) {
                $byTable[$stmt['table']] = [];
            }
            $byTable[$stmt['table']][] = $stmt['sql'];
        }

        $bar = $this->output->createProgressBar(count($statements));
        $bar->start();

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        // Truncate tables if --force
        if ($this->option('force')) {
            $this->info("\nTruncating tables (--force enabled)...");
            foreach (array_keys($byTable) as $table) {
                if (Schema::hasTable($table)) {
                    try {
                        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                        DB::table($table)->truncate();
                        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
                    } catch (\Exception $e) {
                        // Ignore truncate errors
                    }
                }
            }
        }

        foreach ($statements as $stmt) {
            $table = $stmt['table'];
            $statement = $stmt['sql'];

            try {
                // Check if table exists
                if (!Schema::hasTable($table)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                // Check if table has data (unless --force)
                if (!$this->option('force')) {
                    $count = DB::table($table)->count();
                    if ($count > 0) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }
                }
                
                // Use DB::unprepared for raw SQL with complex strings
                DB::unprepared($statement);
                $imported++;
            } catch (\Exception $e) {
                $errors++;
                // Only show first few errors to avoid spam
                if ($errors <= 5) {
                    $this->error("\nError in table {$table}: " . substr($e->getMessage(), 0, 100));
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Import completed!");
        $this->info("Imported: {$imported} statements");
        $this->info("Skipped: {$skipped} statements");
        if ($errors > 0) {
            $this->warn("Errors: {$errors} statements");
            if ($errors > 5) {
                $this->warn("(Only showing first 5 errors)");
            }
        }

        return 0;
    }
}

