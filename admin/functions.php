<?php

function displayTable($pdo, $table, $columns, $displayImageColumns = [], $includeActions = true, $imagePathPrefix = '', $includeArchived = false, $actions = [])
{

    // HOW TO USE
    // $table = 'your_table_name';
    // $columns = ['id', 'name', 'created_at'];
    // $displayImageColumns = ['image_path'];
    // $actions = ['unarchive', 'archive', 'delete']; // Specify the actions you want to include

    // displayTable($pdo, $table, $columns, $displayImageColumns, true, '', false, $actions);


    // Check if the table has the 'created_at' and 'is_archive' columns
    $query = $pdo->prepare("DESCRIBE $table");
    $query->execute();
    $columnsInfo = $query->fetchAll(PDO::FETCH_COLUMN);

    // Determine the ORDER BY clause based on the presence of 'created_at' column
    $orderBy = in_array('created_at', $columnsInfo) ? "ORDER BY created_at DESC" : "";

    // Check if the 'is_archive' column exists and set the WHERE clause accordingly
    $whereClause = "";
    if (in_array('is_archive', $columnsInfo)) {
        $whereClause = $includeArchived ? "WHERE is_archive = 1" : "WHERE is_archive = 0";
    }

    // Query to fetch records from the specified table
    $query = $pdo->prepare("SELECT * FROM $table $whereClause $orderBy");
    $query->execute();

    ?>
    <div class="search-box ms-2 mt-3 mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search for <?php echo $table ?>..." onkeyup="searchTable()">
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
                    echo '<td><a href="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" data-lightbox="image-' . htmlspecialchars($row['id']) . '"><img src="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" alt="Image" style="width: 100px; height: auto;"></a></td>';
                } else {
                    echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
                }
            }
            if ($includeActions) {
                echo '<td>
                        <div style="display: flex; gap: 5px;">';
                foreach ($actions as $action) {
                    if ($action == 'unarchive') {
                        echo '<form method="POST" action="unarchive.php">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                <input type="hidden" name="table" value="' . htmlspecialchars($table) . '">
                                <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                              </form>';
                    } elseif ($action == 'archive') {
                        echo '<form method="POST" action="archive.php">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                                <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                                <button type="submit" name="archive" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to archive this item?\')">
                                    <i class="fa fa-trash"></i> Archive
                                </button>
                              </form>';
                    } elseif ($action == 'delete') {
                        echo '<form method="POST" action="delete.php">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                                <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                                <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                              </form>';
                    }
                }
                echo '</div>
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