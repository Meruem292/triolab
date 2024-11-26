<?php
require "db.php";

session_start();
date_default_timezone_set('Asia/Manila');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

// Function to back up the database
function backupDatabase()
{
    global $pdo;

    // Get all tables except `database_backup`
    $query = $pdo->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_COLUMN);

    // Filter out the `database_backup` table
    $tables = array_filter($tables, function ($table) {
        return $table !== 'database_backup';
    });

    // Initialize SQL dump content
    $sqlDump = "";

    foreach ($tables as $table) {
        // Process each table normally
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $createTableQuery = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $sqlDump .= $createTableQuery['Create Table'] . ";\n\n";

        // Get table data
        $rows = $pdo->query("SELECT * FROM `$table`");
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            $sqlDump .= "INSERT INTO `$table` (" . implode(", ", array_keys($row)) . ") VALUES (" .
                implode(", ", array_map(function ($value) {
                    return is_null($value) ? "NULL" : "'" . addslashes($value) . "'";
                }, $row)) . ");\n";
        }
        $sqlDump .= "\n";
    }

    // Save the backup to a file
    $backupDir = __DIR__ . "/backups";
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0777, true);
    }

    // Generate a unique filename based on the current date/time
    $backupFile = $backupDir . "/backup_" . date("Ymd_His") . ".sql";

    if (file_put_contents($backupFile, $sqlDump)) {
        // Log the backup in the `database_backup` table
        $stmt = $pdo->prepare("INSERT INTO `database_backup` (`path`, `date`, `is_archive`) VALUES (:path, :date, :is_archive)");
        $stmt->execute([
            ':path' => basename($backupFile), // Store the filename
            ':date' => date("Y-m-d H:i:s"),
            ':is_archive' => 0
        ]);
        return true;
    } else {
        return false;
    }
}



// Function to restore the database from a backup
function restoreDatabase($backupFile)
{
    global $pdo;

    // Read the backup SQL file content
    $sql = file_get_contents($backupFile);

    if ($sql === false) {
        echo "Error reading the backup file.";
        return false;
    }

    // Split the file content into individual queries by semicolons
    $queries = explode(";\n", $sql);
    $queries = array_filter($queries, fn($query) => !empty(trim($query))); // Remove empty queries

    // Start a transaction to ensure atomicity
    $pdo->beginTransaction();

    try {
        // Temporarily disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query) && strpos($query, "--") !== 0) { // Skip comments and empty lines
                $pdo->exec($query);
            }
        }

        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1;');

        // Commit the transaction
        $pdo->commit();
        echo "Database restored successfully from the backup file.";
        return true;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "Error restoring database: " . $e->getMessage();
        return false;
    }
}


// Handle backup request
if (isset($_POST['backup'])) {
    $backupStatus = backupDatabase();
    if ($backupStatus === true) {
        $_SESSION['message'] = "Database backup created successfully.";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = $backupStatus;
        $_SESSION['status'] = "error";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle restore request
if (isset($_POST['revert'])) {
    $backupFile = __DIR__ . "/backups/" . basename($_POST['backup_path']);
    $restoreStatus = restoreDatabase($backupFile);

    $_SESSION['message'] = "Database restored successfully.";
    $_SESSION['status'] = "success";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch backups from the database
$stmt = $pdo->query("SELECT id, path, date, is_archive FROM database_backup ORDER BY date DESC");
$backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$admin_id = $_SESSION['user_id'];
?>



<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="green">

<head>
    <meta charset="utf-8" />
    <title>Triolab - Online Healthcare Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="assets/js/layout.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <script>
        // Prevents reloading on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>

<body style="background-color: #F2FFF1">

    <div id="layout-wrapper">
        <?php require "header.php"; ?>
        <?php require "sidebar.php" ?>
        <?php require "functions.php"; ?>
        <div class="vertical-overlay"></div>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Backup and Restore</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Backup and Restore</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Panel -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="payment-modes-tab" data-bs-toggle="tab" data-bs-target="#payment-modes" type="button" role="tab" aria-controls="payment-modes" aria-selected="true">Backup and Restore</button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-receipts-tab" data-bs-toggle="tab" data-bs-target="#payment-receipts" type="button" role="tab" aria-controls="payment-receipts" aria-selected="false">Backup Archives</button>
                        </li> -->
                    </ul>


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Button to trigger modal -->
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="payment-modes" role="tabpanel" aria-labelledby="payment-modes-tab">
                                            <div class="row mb-2 mt-3">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <form method="POST">
                                                            <button class="btn btn-primary" type="submit" name="backup"><i class="fas fa-database"></i> BACKUP DATABASE</button>
                                                        </form>

                                                        <h4 class="mt-3">Backup and Restore</h4>

                                                        <form method="POST">
                                                            <div class="form-group">
                                                                <label for="backup">Choose Backup File to Restore</label>
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Path</th>
                                                                            <th>Date</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (count($backups) > 0): ?>
                                                                            <?php foreach ($backups as $backup): ?>
                                                                                <tr>
                                                                                    <td><?php echo htmlspecialchars($backup['id']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($backup['path']); ?></td>
                                                                                    <td><?php echo htmlspecialchars($backup['date']); ?></td>
                                                                                    <td>
                                                                                        <form method="POST" style="display:inline;">
                                                                                            <input type="hidden" name="backup_path" value="<?php echo htmlspecialchars($backup['path']); ?>">
                                                                                            <button type="submit" name="revert" class="btn btn-warning btn-sm">
                                                                                                <i class="fas fa-undo"></i> Revert
                                                                                            </button>
                                                                                        </form>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endforeach; ?>
                                                                        <?php else: ?>
                                                                            <tr>
                                                                                <td colspan="4" class="text-center">No backups found.</td>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </form>



                                                    </div>
                                                </div>
                                            </div><!-- end card-body -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php require "footer.php"; ?>
            </div>
        </div>

        <!-- Button to go to top -->
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>


        <!-- Bootstrap JS (includes Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="assets/js/pages/listjs.init.js"></script>
        <script src="assets/js/app.js"></script>
        <script src="assets/js/sweetalert.js"></script>




        <!-- Show SweetAlert message -->
        <?php if (isset($_SESSION['message']) && isset($_SESSION['status'])) { ?>
            <script>
                Swal.fire({
                    text: "<?php echo $_SESSION['message']; ?>",
                    icon: "<?php echo $_SESSION['status']; ?>",
                });
            </script>
        <?php
            unset($_SESSION['message']);
            unset($_SESSION['status']);
        } ?>

</body>

</html>