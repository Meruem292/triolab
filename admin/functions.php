<?php
include "db.php";

function displayTable($pdo, $table, $columns, $displayImageColumns = [], $includeActions = true, $imagePathPrefix = '')
{
    //HOW TO USE THIS FUNCTION
    // $table = 'payment_mode';
    // $columns = array('id', 'method', 'image_path', 'updated_at');
    // $extended_image_path = '../admin/modals/';

    // // Updated to pass image path prefix and column names correctly
    // displayTable($pdo, $table, $columns, ['image_path'], false, $extended_image_path);

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

?>
    <div class="search-box ms-2 mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for patients..." onkeyup="searchTable()">
        <i class="ri-search-line search-icon"></i>
    </div>
    <?php
    // Start the table
    echo '<table id="' . $table . 'Table" class="table table-bordered table-striped">';
    echo '<thead><tr>';

    // Dynamically generate table headers based on the provided columns
    foreach ($columns as $column) {
        $header = ucfirst(str_replace('_', ' ', $column));
        echo "<th>$header</th>";
    }

    // Add Options column if required
    if ($includeActions) {
        echo '<th style="width: 20%;">Options</th>';
    }
    echo '</tr></thead><tbody>';

    // Fetch and display the data
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $column) {
                if (in_array($column, $displayImageColumns)) {
                    echo '<td><img src="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" alt="Image" style="width: 100px; height: auto;"></td>';
                } else {
                    echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
                }
            }
            if ($includeActions) {
                echo '<td>
                        <div style="display: flex; gap: 5px;">
                            <form method="POST" action="unarchive.php">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                <input type="hidden" name="table" value="' . htmlspecialchars($table) . '">
                                <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                            </form>
                            <form method="post" action="delete.php">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                                <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                                <button type="submit" name="archive" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to Delete this log?\')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                      </td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . (count($columns) + ($includeActions ? 1 : 0)) . '">No data available.</td></tr>';
    }

    echo '</tbody></table>';

    // Include the DataTable script
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
    ?>
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("<?php echo $table; ?>Table");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td.length > 0) {
                    var showRow = false;
                    for (var j = 0; j < td.length; j++) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            showRow = true;
                            break; // Stop looking at other columns for this row
                        }
                    }
                    if (showRow) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
<?php
}
?>