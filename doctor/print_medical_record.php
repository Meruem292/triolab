<?php
// Include database connection
include 'db.php'; // Ensure this file properly sets up the $pdo PDO connection.

if (isset($_GET['record_id'])) {
    $record_id = intval($_GET['record_id']); // Validate the record_id as an integer.

    try {
        // Fetch the record from the database
        $query = "SELECT * FROM medical_records WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$record_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as an associative array.

        if ($record) {
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
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($record['id']); ?></p>
                    <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($record['patient_id']); ?></p>
                    <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($record['diagnosis']); ?></p>
                    <p><strong>Treatment:</strong> <?php echo htmlspecialchars($record['treatment']); ?></p>
                    <p><strong>Record Date:</strong> <?php echo htmlspecialchars($record['record_date']); ?></p>
                    <p><strong>Created At:</strong> <?php echo htmlspecialchars($record['created_at']); ?></p>
                    <p><strong>Updated At:</strong> <?php echo htmlspecialchars($record['updated_at']); ?></p>
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
    } catch (PDOException $e) {
        echo "Error fetching record: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
