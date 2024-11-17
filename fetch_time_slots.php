<?php

require "db.php";

if (isset($_GET['selectedDate'])) {
    $selectedDate = $_GET['selectedDate'];
    // Fetch available time slots based on the selected date
    $slotQuery = $pdo->prepare("SELECT * FROM appointment_slots WHERE date = ?");
    $slotQuery->execute([$selectedDate]);
    $slotFetchAll = $slotQuery->fetchAll(PDO::FETCH_ASSOC);

    if ($slotFetchAll) {
        // Encode the time slots data into JSON format
        echo json_encode($slotFetchAll);
    } else {
        // If no slots available, return a JSON object with a message
        echo json_encode(array('message' => 'No available slots for the selected date.'));
    }
}
?>
