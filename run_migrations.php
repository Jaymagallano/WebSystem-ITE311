#!/usr/bin/env php
<?php
/**
 * Migration Runner Script
 * 
 * This script automatically runs all pending migrations
 * to ensure the database schema is up-to-date.
 */

// Define environment
define('ENVIRONMENT', 'development');

// Define paths
define('BASEPATH', __DIR__);
$system_path = 'system';
$application_folder = 'application';

// Set up the environment
if (realpath($system_path) !== FALSE)
{
    $system_path = realpath($system_path).'/';
}

$system_path = rtrim($system_path, '/').'/';

// Path to the system folder
define('SYSDIR', trim(strrchr(trim($system_path, '/'), '/'), '/'));

// Path to the front controller (this file)
define('FCPATH', __DIR__.'/');

// Path to the application folder
define('APPPATH', $application_folder.'/');

// Load database configuration
require_once APPPATH.'config/database.php';

try {
    // Connect to database
    $pdo = new PDO("mysql:host={$db['default']['hostname']};dbname={$db['default']['database']}", 
                   $db['default']['username'], 
                   $db['default']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the latest migration file number
    $migrationFiles = glob(APPPATH.'migrations/0*.php');
    $latestMigration = 0;
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file);
        $version = intval(substr($filename, 0, 3));
        if ($version > $latestMigration) {
            $latestMigration = $version;
        }
    }
    
    // Get current migration version from database
    $stmt = $pdo->query("SELECT version FROM migrations");
    $currentVersion = $stmt->fetchColumn();
    
    echo "Current migration version: $currentVersion\n";
    echo "Latest migration version: $latestMigration\n";
    
    if ($currentVersion < $latestMigration) {
        echo "Running migrations...\n";
        
        // Execute the migration via the web interface
        $url = "http://localhost/ITE311-MAGALLANO/index.php/migrate";
        $result = @file_get_contents($url);
        
        if ($result !== false) {
            echo "✅ Migrations completed successfully!\n";
        } else {
            echo "⚠️  Could not automatically run migrations. Please visit:\n";
            echo "   $url\n";
        }
    } else {
        echo "✅ Database is already up-to-date.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>