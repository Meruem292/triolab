<?php
session_start();
require 'db.php'; // Assuming this is where the database connection is established.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic FullCalendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <h1>Dynamic Appointment Calendar</h1>

    <label for="category-select">Select Category:</label>
    <select id="category-select">
        <option value="">Select a category</option>
        <?php
        try {
            // Fetch unique categories
            $stmt = $pdo->prepare("SELECT DISTINCT category FROM services WHERE is_archive = 0");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['category']}'>{$row['category']}</option>";
            }
        } catch (Exception $e) {
            echo "<option value=''>Error loading categories</option>";
        }
        ?>
    </select>

    <label for="type-select">Select Type:</label>
    <select id="type-select" disabled>
        <option value="">Select a type</option>
    </select>

    <label for="service-select">Select Service:</label>
    <select id="service-select" disabled>
        <option value="">Select a service</option>
    </select>

    <div id="calendar"></div>

    <script>
        $(document).ready(function () {
            // Populate types based on category selection
            $('#category-select').on('change', function () {
                const category = $(this).val();

                $('#type-select').prop('disabled', !category);
                $('#service-select').prop('disabled', true).html('<option value="">Select a service</option>');

                if (category) {
                    $.ajax({
                        url: 'fetch_types.php',
                        type: 'POST',
                        data: { category },
                        success: function (response) {
                            const types = JSON.parse(response);
                            let options = '<option value="">Select a type</option>';
                            types.forEach(type => {
                                options += `<option value="${type.type}">${type.type}</option>`;
                            });
                            $('#type-select').html(options);
                        },
                        error: function () {
                            alert('Error loading types.');
                        }
                    });
                } else {
                    $('#type-select').html('<option value="">Select a type</option>');
                }
            });

            // Populate services based on type selection
            $('#type-select').on('change', function () {
                const type = $(this).val();

                $('#service-select').prop('disabled', !type);

                if (type) {
                    $.ajax({
                        url: 'fetch_services.php',
                        type: 'POST',
                        data: { type },
                        success: function (response) {
                            const services = JSON.parse(response);
                            let options = '<option value="">Select a service</option>';
                            services.forEach(service => {
                                options += `<option value="${service.id}">${service.service}</option>`;
                            });
                            $('#service-select').html(options);
                        },
                        error: function () {
                            alert('Error loading services.');
                        }
                    });
                } else {
                    $('#service-select').html('<option value="">Select a service</option>');
                }
            });

            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function (info, successCallback, failureCallback) {
                    const selectedServiceId = $('#service-select').val();

                    if (!selectedServiceId) {
                        successCallback([]); // Clear calendar if no service is selected
                        return;
                    }

                    $.ajax({
                        url: 'get_appointment_slots.php',
                        type: 'POST',
                        data: {
                            service_id: selectedServiceId,
                            start_date: info.startStr,
                            end_date: info.endStr
                        },
                        success: function (response) {
                            try {
                                const slots = JSON.parse(response);
                                const events = slots.map(slot => ({
                                    title: `Available Slot: ${slot.slot}`,
                                    start: slot.date
                                }));
                                successCallback(events);
                            } catch (e) {
                                console.error('Error parsing response:', e, response);
                                failureCallback();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error:', status, error);
                            failureCallback();
                        }
                    });
                }
            });

            calendar.render();

            // Refetch events when the selected service changes
            $('#service-select').on('change', function () {
                calendar.refetchEvents();
            });
        });
    </script>
</body>

</html>
