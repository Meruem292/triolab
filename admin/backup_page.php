<?php
// Database credentials
$username = 'root';
$password = '';
$database = 'triolab';
$host = 'localhost';

// Backup directory path (ensure this directory exists and is writable)
$backupDir = __DIR__ . '/backups/';

// Create a new PDO connection
try {
    $dsn = "mysql:host=$host;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; // Exit the script if the connection fails
}

// Ensure the backup directory exists, create if not
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        die("Failed to create backup directory: $backupDir");
    }
}

// Set up backup filename with timestamp
$filename = 'backup_' . date("Y-m-d-H-i-s") . '.sql';
$filePath = $backupDir . $filename;

try {
    // Fetch all table names in the database
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    // Open file for writing
    $handle = fopen($filePath, 'w');
    if (!$handle) {
        throw new Exception("Could not open file for writing: $filePath");
    }

    // Write the backup file header
    fwrite($handle, "-- MySQL Database Backup" . PHP_EOL);
    fwrite($handle, "-- Generated on: " . date("Y-m-d H:i:s") . PHP_EOL . PHP_EOL);

    // Export structure and data for each table
    foreach ($tables as $table) {
        // Write table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $tableData = $stmt->fetch(PDO::FETCH_NUM);
        fwrite($handle, "-- Table structure for table `$table`" . PHP_EOL);
        fwrite($handle, $tableData[1] . ";" . PHP_EOL . PHP_EOL);

        // Write table data
        $stmt = $pdo->query("SELECT * FROM `$table`");
        if ($stmt->rowCount() > 0) {
            fwrite($handle, "-- Dumping data for table `$table`" . PHP_EOL);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Escape each value properly
                $rowValues = array_map(function ($value) {
                    return "'" . addslashes($value) . "'"; // You can change this for more advanced escaping
                }, $row);
                $rowValuesString = implode(', ', $rowValues);
                fwrite($handle, "INSERT INTO `$table` VALUES ($rowValuesString);" . PHP_EOL);
            }
            fwrite($handle, PHP_EOL);
        }
    }

    fclose($handle);

    // Verify backup file
    if (file_exists($filePath) && filesize($filePath) > 0) {
        echo "Backup successful! File created at: $filePath";
        // Optionally, you could redirect or provide a download link to the backup file
    } else {
        throw new Exception("Backup failed or produced an empty file.");
    }
} catch (Exception $e) {
    // Handle any error during the backup process
    echo 'Backup failed: ' . $e->getMessage();
    exit; // Exit the script if backup fails
}
?>
