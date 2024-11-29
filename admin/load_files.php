<?php

require 'db.php';
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $stmt = $pdo->prepare("SELECT id, directory, file_name, uploaded_at FROM patient_files WHERE patient_id = ?");
    $stmt->execute([$userId]);
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($files) > 0) {
        echo '<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>File Name</th>
                        <th>Upload Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($files as $file) {
            echo '<tr>
                    <td>' . $file['id'] . '</td>
                    <td>' . htmlspecialchars($file['file_name']) . '</td>
                    <td>' . $file['uploaded_at'] . '</td>
                    <td><a href="' . $file['directory'] . '/' . htmlspecialchars($file['file_name']) . '" target="_blank" class="btn btn-primary btn-sm">View</a></td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info" role="alert">No files found for this user.</div>';
    }
}
?>

<!-- Include Bootstrap and DataTables CSS & JS -->




<script>
    $(document).ready(function() {
        $('#example').DataTable(); // Initialize DataTable
    });
</script>
