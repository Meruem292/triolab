<?php
session_start();
require 'db.php'; // Database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic FullCalendar with Appointment Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Dynamic Appointment Calendar with Cart</h1>

        <div class="mb-3">
            <label for="category-select" class="form-label">Select Category:</label>
            <select id="category-select" class="form-select">
                <option value="">Select a category</option>
                <?php
                try {
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
        </div>
        <div class="mb-3">
            <label for="type-select" class="form-label">Select Type:</label>
            <select id="type-select" class="form-select" disabled>
                <option value="">Select a type</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="service-select" class="form-label">Select Service:</label>
            <select id="service-select" class="form-select" disabled>
                <option value="">Select a service</option>
            </select>
        </div>

        <div id="calendar" class="mb-4"></div>

        <div id="cart" class="card">
            <div class="card-header">Appointment Cart</div>
            <ul id="appointment-cart" class="list-group list-group-flush">
                <li class="list-group-item">No appointments added yet.</li>
            </ul>
            <button id="proceed-to-appointment" class="btn btn-primary">Proceed to Appointment</button>
        </div>
    </div>

    <div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointment-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointment-modal-label">Appointment Slot Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modal-details"></p>
                    <div class="mb-3">
                        <label for="flatpickr" class="form-label">Select Date and Time:</label>
                        <input type="text" id="flatpickr" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="add-to-cart" type="button" class="btn btn-primary">Add to Appointment</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            const calendarEl = document.getElementById('calendar');
            let selectedDate = null;
            let cart = [];

            // FullCalendar initialization
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(info, successCallback, failureCallback) {
                    const selectedServiceId = $('#service-select').val();

                    if (!selectedServiceId) {
                        successCallback([]);
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
                        success: function(response) {
                            try {
                                const slots = JSON.parse(response);
                                const events = slots.map(slot => ({
                                    id: slot.appointment_slot_id,
                                    title: `${slot.doctor_name} - ${slot.department_name}`,
                                    start: slot.date,
                                    extendedProps: {
                                        slotId: slot.appointment_slot_id,
                                        slot: slot.slot,
                                        date: slot.date,
                                        doctorName: slot.doctor_name,
                                        departmentName: slot.department_name,
                                        doctorId: slot.doctor_id,
                                        departmentId: slot.department_id
                                    }
                                }));
                                successCallback(events);
                            } catch (e) {
                                console.error('Error parsing response:', e, response);
                                failureCallback();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', status, error);
                            failureCallback();
                        }
                    });
                },

                eventClick: function(info) {
                    const {
                        slotId,
                        slot,
                        date,
                        doctorName,
                        departmentName,
                        doctorId, // Doctor's ID
                        departmentId // Department's ID
                    } = info.event.extendedProps;

                    const formattedDate = new Date(date).toISOString().split('T')[0];

                    // Update modal with doctor's ID and department ID
                    $('#modal-details').html(`
                        
                        <p><strong>Doctor:</strong> ${doctorName}</p>
                        <p><strong>Department:</strong> ${departmentName}</p>
                        <p><strong>Slot:</strong> ${slot}</p>
                        <p><strong>Date:</strong> ${formattedDate}</p>
                    `);

                    // Store the doctorId and departmentId for later use
                    $('#appointment-modal').data('slotId', slotId);
                    $('#appointment-modal').data('doctorId', doctorId);
                    $('#appointment-modal').data('departmentId', departmentId);

                    selectedDate = formattedDate;
                    $('#appointment-modal').modal('show');
                }
            });

            calendar.render();

            // Category selection
            $('#category-select').on('change', function() {
                const category = $(this).val();
                $('#type-select').prop('disabled', !category).html('<option value="">Select a type</option>');
                $('#service-select').prop('disabled', true).html('<option value="">Select a service</option>');

                if (category) {
                    $.ajax({
                        url: 'fetch_types.php',
                        type: 'POST',
                        data: {
                            category
                        },
                        success: function(response) {
                            const types = JSON.parse(response);
                            let options = '<option value="">Select a type</option>';
                            types.forEach(type => {
                                options += `<option value="${type.type}">${type.type}</option>`;
                            });
                            $('#type-select').html(options);
                        },
                        error: function() {
                            alert('Error loading types.');
                        }
                    });
                }
            });

            // Type selection
            $('#type-select').on('change', function() {
                const type = $(this).val();
                $('#service-select').prop('disabled', !type).html('<option value="">Select a service</option>');

                if (type) {
                    $.ajax({
                        url: 'fetch_services.php',
                        type: 'POST',
                        data: {
                            type
                        },
                        success: function(response) {
                            const services = JSON.parse(response);
                            let options = '<option value="">Select a service</option>';
                            services.forEach(service => {
                                options += `<option value="${service.id}">${service.service}</option>`;
                            });
                            $('#service-select').html(options);
                        },
                        error: function() {
                            alert('Error loading services.');
                        }
                    });
                }
            });

            // Flatpickr initialization
            flatpickr("#flatpickr", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            });

            // Refetch events when a new service is selected
            $('#service-select').on('change', function() {
                calendar.refetchEvents();
            });

            $('#add-to-cart').on('click', function() {
                const selectedServiceId = $('#service-select').val();
                const selectedTime = $('#flatpickr').val();

                // Retrieve doctorId and departmentId from modal data

                const slotId = $('#appointment-modal').data('slotId');
                const doctorId = $('#appointment-modal').data('doctorId');
                const departmentId = $('#appointment-modal').data('departmentId');

                if (!selectedDate) {
                    alert('No appointment slot selected!');
                    return;
                }

                if (!selectedTime) {
                    alert('Please select a time for the appointment!');
                    return;
                }

                if (cart.length > 0 && cart[0].date !== selectedDate) {
                    alert('You cannot add multiple services with a different day.');
                    return;
                }

                // Add the selected appointment to the cart
                cart.push({
                    serviceId: selectedServiceId,
                    date: selectedDate,
                    time: selectedTime,
                    doctorId: doctorId, // Store doctor ID
                    departmentId: departmentId, // Store department ID
                    slotId: slotId // Store slot ID
                });



                updateCart();
                $('#appointment-modal').modal('hide');
            });


            function updateCart() {
                const cartList = $('#appointment-cart');
                cartList.empty();

                if (cart.length === 0) {
                    cartList.append('<li class="list-group-item">No appointments added yet.</li>');
                } else {
                    cart.forEach((item, index) => {
                        cartList.append(`
                            <li class="list-group-item">
                                Date: ${item.date}, Time: ${item.time}, Service ID: ${item.serviceId}, 
                                Doctor ID: ${item.doctorId}, Department ID: ${item.departmentId}
                                <button class="btn btn-danger btn-sm float-right remove-from-cart" data-index="${index}">Remove</button>
                            </li>
                        `);
                    });
                }
            }

            $(document).on('click', '.remove-from-cart', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
            });

            // Proceed to appointment
            $('#proceed-to-appointment').on('click', function() {
                if (cart.length === 0) {
                    alert('Please add at least one appointment to proceed.');
                    return;
                }

                // Handle the submission of appointments
                $.ajax({
                    url: 'submit_appointments.php',
                    type: 'POST',
                    data: {
                        appointments: JSON.stringify(cart)
                    }, // Send cart data as JSON
                    success: function(response) {
                        console.log(response); // Check if the success message is returned from PHP
                        alert('Appointments submitted successfully!');
                        cart = [];
                        updateCart();
                    },
                    error: function(xhr, status, error) {
                        alert('Error submitting appointments: ' + error);
                    }
                });

            });
        });
    </script>
</body>

</html>