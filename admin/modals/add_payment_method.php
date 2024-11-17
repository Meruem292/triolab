<?php
include "db.php";

// modal for adding payment method

?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
    Add Payment Method
</button>

<div class="modal fade" id="addPaymentMethodModal" tabindex="-1" aria-labelledby="addPaymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentMethodModalLabel">Update Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentMethodForm" enctype="multipart/form-data" action="../admin/modals/api.php" method="POST">
                <div class="modal-body">
                
                    <!-- input payment method -->
                    <div class="form-group">
                        <label for="methodName">Input Payment Method</label>
                        <input type="text" class="form-control" id="methodName" name="method" required>
                    </div>

                    <!-- Input for updating the image -->
                    <div class="form-group mt-3">
                        <label for="methodImage">Add Payment Method Image (Optional)</label>
                        <input type="file" class="form-control-file" id="methodImage" name="image_path">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add_payment_method" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php


?>