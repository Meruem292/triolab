<?php
// Include database connection
include 'db_conn.php';

if (isset($_GET['record_id'])) {
    $record_id = intval($_GET['record_id']);

    // Fetch the record from the database
    $query = "SELECT * FROM medical_records WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $record_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Medical Record</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .record-container { border: 1px solid #ccc; padding: 20px; }
                h2 { text-align: center; }
            </style>
        </head>
        <body>
            <div class="record-container">
                <h2>Medical Record</h2>
                <p><strong>ID:</strong> <?php echo $record['id']; ?></p>
                <p><strong>Patient ID:</strong> <?php echo $record['patient_id']; ?></p>
                <p><strong>Diagnosis:</strong> <?php echo $record['diagnosis']; ?></p>
                <p><strong>Treatment:</strong> <?php echo $record['treatment']; ?></p>
                <p><strong>Record Date:</strong> <?php echo $record['record_date']; ?></p>
                <p><strong>Created At:</strong> <?php echo $record['created_at']; ?></p>
                <p><strong>Updated At:</strong> <?php echo $record['updated_at']; ?></p>
            </div>
            <script>
                window.onload = function() { window.print(); }
            </script>
        </body>
        </html>
        <?php
    } else {
        echo "No record found.";
    }
} else {
    echo "Invalid request.";
}
?>
