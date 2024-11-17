<?php
// Fetch payment methods from the database
require_once 'db.php';  // Ensure database connection is included
$query = "SELECT * FROM payment_mode WHERE id != 3";  // Fetch non-archived payment methods
$stmt = $pdo->prepare($query);
$stmt->execute();
$payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPaymentMethodModal">
    Edit Payment Method
</button>

<!-- Modal -->
<div class="modal fade" id="uploadPaymentMethodModal" tabindex="-1" aria-labelledby="uploadPaymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPaymentMethodModalLabel">Update Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentMethodForm" enctype="multipart/form-data" action="../admin/modals/api.php" method="POST">
                <div class="modal-body">
                    <!-- Display the current image dynamically based on selection -->
                    <div class="form-group" id="currentImageContainer" style="display: none;">
                        <img src="" id="currentImage" class="img-fluid" width="100" alt="Current Payment Method Image">
                    </div>
                    
                    <!-- Select payment method -->
                    <div class="form-group">
                        <label for="methodName">Select Payment Method</label>
                        <select class="form-control" id="methodName" name="method_id" required>
                            <option value="">Select a Payment Method</option>
                            <?php foreach ($payment_methods as $method): ?>
                                <option value="<?= $method['id'] ?>" data-image="<?= $method['image_path'] ?>"><?= $method['method'] ?></option>
                                
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Input for updating the image -->
                    <div class="form-group mt-3">
                        <label for="methodImage">Upload Payment Method Image (Optional)</label>
                        <input type="file" class="form-control-file" id="methodImage" name="image_path">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="upload_payment_method" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // When the modal is shown, update the current image if a payment method is selected
    $('#uploadPaymentMethodModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Set the selected option in the select dropdown
        var selectedOption = modal.find('#methodName option:selected');
        var currentImageSrc = selectedOption.data('image'); // Get the current image path

        // If an image exists, display it
        if (currentImageSrc) {
            modal.find('#currentImage').attr('src', currentImageSrc);
            modal.find('#currentImageContainer').show(); // Show image container
        } else {
            modal.find('#currentImageContainer').hide(); // Hide image container if no image
        }
    });

    // Update the current image dynamically when selecting a payment method
    $('#methodName').change(function() {
        var selectedOption = $(this).find('option:selected');
        var imagePath = selectedOption.data('image');
        
        if (imagePath) {
            $('#currentImage').attr('src', imagePath);
            $('#currentImageContainer').show();
        } else {
            $('#currentImageContainer').hide();
        }
    });
</script>
