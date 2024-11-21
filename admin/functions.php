<?php


function displayTable($pdo, $table, $columns, $displayImageColumns = [], $includeActions = true, $imagePathPrefix = '', $includeArchived = false, $actions = [])
{
    // Check if the table has 'created_at' and 'is_archive' columns
    $query = $pdo->prepare("DESCRIBE $table");
    $query->execute();
    $columnsInfo = $query->fetchAll(PDO::FETCH_COLUMN);

    // Determine the ORDER BY clause based on 'created_at' column presence
    $orderBy = in_array('created_at', $columnsInfo) ? "ORDER BY created_at DESC" : "";

    // Handle the WHERE clause based on the presence of 'is_archive' column
    $whereClause = "";
    if (in_array('is_archive', $columnsInfo)) {
        $whereClause = $includeArchived ? "WHERE is_archive = 1" : "WHERE is_archive = 0";
    }

    // Fetch data from the specified table
    $query = $pdo->prepare("SELECT * FROM $table $whereClause $orderBy");
    $query->execute();

    // Start the table
    echo '<div class="search-box ms-2 mt-3 mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for ' . htmlspecialchars($table) . '..." onkeyup="searchTable()">
            <i class="ri-search-line search-icon"></i>
          </div>';

    echo '<table id="' . htmlspecialchars($table) . 'Table" class="table table-bordered table-striped">';
    echo '<thead><tr>';

    // Generate table headers dynamically
    foreach ($columns as $column) {
        $header = ucfirst(str_replace('_', ' ', $column));
        echo "<th>" . htmlspecialchars($header) . "</th>";
    }

    // Add Options column if required
    if ($includeActions) {
        echo '<th style="width: 20%;">Options</th>';
    }
    echo '</tr></thead><tbody>';

    // Fetch and display table rows
    if ($query->rowCount() > 0) {
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            foreach ($columns as $column) {
                if (in_array($column, $displayImageColumns)) {
                    echo '<td><a href="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" data-lightbox="gallery" data-lightbox="image-' . htmlspecialchars($row['id']) . '">
                            <img src="' . htmlspecialchars($imagePathPrefix . $row[$column]) . '" alt="Image" style="width: 100px; height: auto;">
                          </a></td>';
                } else {
                    echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
                }
            }
            if ($includeActions) {
                echo '<td><div style="display: flex; gap: 5px;">';
                foreach ($actions as $action) {
                    handleActionButton($pdo, $action, $row, $table);
                }
                echo '</div></td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="' . (count($columns) + ($includeActions ? 1 : 0)) . '">No data available.</td></tr>';
    }

    echo '</tbody></table>';

    // Include DataTable functionality
    echo '<script>
            $(document).ready(function() {
                $("#' . htmlspecialchars($table) . 'Table").DataTable({
                    "aoColumnDefs": [{
                        "bSortable": false,
                        "aTargets": [' . count($columns) . '] // Disable sorting for the options column
                    }],
                    "aaSorting": []
                });
            });
          </script>';

    // JavaScript for table search functionality
    echo '<script>
            function searchTable() {
                const input = document.getElementById("searchInput");
                const filter = input.value.toUpperCase();
                const table = document.getElementById("' . htmlspecialchars($table) . 'Table");
                const tr = table.getElementsByTagName("tr");

                for (let i = 0; i < tr.length; i++) {
                    const td = tr[i].getElementsByTagName("td");
                    if (td.length > 0) {
                        let showRow = false;
                        for (let j = 0; j < td.length; j++) {
                            const txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                showRow = true;
                                break;
                            }
                        }
                        tr[i].style.display = showRow ? "" : "none";
                    }
                }
            }
          </script>';
}

function handleActionButton($pdo, $action, $row, $table)
{
    switch ($action) {
        case 'unarchive':
            echo '<form method="POST" action="unarchive.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '">
                    <button class="btn btn-primary btn-sm" type="submit" name="unarchive" value="1">Unarchive</button>
                  </form>';
            break;
        case 'archive':
            echo '<form method="POST" action="archive.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                    <button type="submit" name="archive" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to archive this item?\')">
                        <i class="fa fa-trash"></i> Archive
                    </button>
                  </form>';
            break;
        case 'delete':
            echo '<form method="POST" action="delete.php">
                    <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '" />
                    <input type="hidden" name="table" value="' . htmlspecialchars($table) . '" />
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                  </form>';
            break;
        case 'edit_payment_receipt':
            echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#testModal"
                        data-id="' . htmlspecialchars($row['id']) . '"
                        data-status="' . htmlspecialchars($row['status']) . '">
                        Edit
                    </button>';
            echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        $("#testModal").on("show.bs.modal", function(event) {
                            const button = $(event.relatedTarget);
                            const id = button.data("id");
                            const status = button.data("status");
                            const modal = $(this);
                            modal.find("#id").val(id);
                            modal.find("#status").val(status);
                        });
                    });
                  </script>';
            editFormPayments($pdo);
            break;
    }
}

function editFormPayments($pdo)
{
    // Modal for editing payment
?><div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalLabel">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="action.php">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="status" class="mt-2">Status</label>
                            <select name="status" id="status" class="form-control w-50" style="margin-left: -30%;">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Disapproved">Disapproved</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_payment" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
