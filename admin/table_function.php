<?php
function unArchiveData($pdo, $table, $id)
{
    try {
        // Check if the table exists
        $stmt = $pdo->prepare("SHOW TABLES LIKE :table");
        $stmt->execute([':table' => $table]);

        if ($stmt->rowCount() == 0) {
            return "Error: Table '$table' does not exist.";
        }

        $sql = "UPDATE $table SET is_archive = 0 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        // Bind the parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            return "Record unarchived successfully.";
        } else {
            return "Error: Unable to unarchive record.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function delateData($pdo, $table, $id)
{
    try {
        // Check if the table exists
        $stmt = $pdo->prepare("SHOW TABLES LIKE :table");
        $stmt->execute([':table' => $table]);

        if ($stmt->rowCount() == 0) {
            return "Error: Table '$table' does not exist.";
        }

        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        // Bind the parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            return "Record deleted successfully.";
        } else {
            return "Error: Unable to delete record.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function displayTable($pdo, $table, $columns)
{

    // Check if the table has the 'created_at' and 'is_archive' columns
    $query = $pdo->prepare("DESCRIBE $table");
    $query->execute();
    $columnsInfo = $query->fetchAll(PDO::FETCH_COLUMN);

    // Determine the ORDER BY clause based on the presence of 'created_at' column
    $orderBy = in_array('created_at', $columnsInfo) ? "ORDER BY created_at DESC" : "";

    // Check if the 'is_archive' column exists and set the WHERE clause accordingly
    $whereClause = in_array('is_archive', $columnsInfo) ? "WHERE is_archive = 1" : "";

    // Query to fetch records from the specified table
    $query = $pdo->prepare("SELECT * FROM $table $whereClause $orderBy");
    $query->execute();

    // Start the table
    echo '<table id="' . $table . 'Table" class="table table-bordered table-striped">';
    echo '<thead><tr>';

    // Dynamically generate table headers based on the provided columns
    foreach ($columns as $column) {
        $header = ucfirst(str_replace('_', ' ', $column));
        echo "<th>$header</th>";
    }

    // Add Options column
    echo '<th style="width: 20%;">Options</th>';
    echo '</tr></thead><tbody>';

    // Fetch and display the data
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $column) {
                echo '<td>' . $row[$column] . '</td>';
            }
            echo '<td>
                    <div style="display: flex; gap: 5px;">
                        <form method="POST" action="unarchive.php" id="unarchiveForm_' . $row['id'] . '">
                            <input type="hidden" name="id" value="' . $row['id'] . '">
                            <input type="hidden" name="table" value="' . $table . '">
                            <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                        </form>
                    </div>
                  </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . (count($columns) + 1) . '">No data available.</td></tr>';
    }

    echo '</tbody></table>';

    // Include the DataTable script and jQuery CDN (make sure they are included correctly)



    // DataTables initialization
    echo '<script>
            $(document).ready(function() {
                $("#' . $table . 'Table").DataTable({
                    "aoColumnDefs": [{
                        "bSortable": false,
                        "aTargets": [' . count($columns) . '] // Disable sorting for the options column
                    }],
                    "aaSorting": []
                });
            });
          </script>';
}

function displayTableWithArchive($pdo, $table, $columns)
{
    // Check if the table has the 'created_at' and 'is_archive' columns
    $query = $pdo->prepare("DESCRIBE $table");
    $query->execute();
    $columnsInfo = $query->fetchAll(PDO::FETCH_COLUMN);

    // Determine the ORDER BY clause based on the presence of 'created_at' column
    $orderBy = in_array('created_at', $columnsInfo) ? "ORDER BY created_at DESC" : "";

    // Check if the 'is_archive' column exists and set the WHERE clause accordingly
    $whereClause = in_array('is_archive', $columnsInfo) ? "WHERE is_archive = 1" : "";

    // Query to fetch records from the specified table
    $query = $pdo->prepare("SELECT * FROM $table $whereClause $orderBy");
    $query->execute();

    // Start the table
    echo '<table id="' . $table . 'Table" class="table table-bordered table-striped">';
    echo '<thead><tr>';

    // Dynamically generate table headers based on the provided columns
    foreach ($columns as $column) {
        $header = ucfirst(str_replace('_', ' ', $column));
        echo "<th>$header</th>";
    }

    // Add Options column
    echo '<th style="width: 20%;">Options</th>';
    echo '</tr></thead><tbody>';

    // Fetch and display the data
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $column) {
                // Check for 'employee_id' column specifically for doctor table
                if ($table == 'doctor' && $column == 'employee_id') {
                    echo '<td>' . $row['employee_id'] . '</td>';
                } else {
                    echo '<td>' . $row[$column] . '</td>';
                }
            }
            echo '<td>
                    <div style="display: flex; gap: 5px;">
                        <form method="POST" action="unarchive.php" id="unarchiveForm_' . $row['employee_id'] . '">
                            <input type="hidden" name="id" value="' . $row['employee_id'] . '">
                            <input type="hidden" name="table" value="' . $table . '">
                            <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                        </form>
                    </div>
                  </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . (count($columns) + 1) . '">No data available.</td></tr>';
    }

    echo '</tbody></table>';

    // Include the DataTable script and jQuery CDN (make sure they are included correctly)
    echo '<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>';
    echo '<script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>';
    echo '<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/jquery.dataTables.min.css">';

    // DataTables initialization
    echo '<script>
            $(document).ready(function() {
                $("#' . $table . 'Table").DataTable({
                    "aoColumnDefs": [{
                        "bSortable": false,
                        "aTargets": [' . count($columns) . '] // Disable sorting for the options column
                    }],
                    "aaSorting": []
                });
            });
          </script>';
}
