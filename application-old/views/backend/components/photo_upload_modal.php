<div class="modal fade" id="uploadImage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $title; ?></h4>
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
            </div>
            <?php
            $attributes = array('id' => 'upload-form', 'enctype' => 'multipart/form-data');
            echo form_open($module_site_url . "/replace_cover_photo/" . $img_type . "/" . $img_parent_id, $attributes);
            ?>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label"><?php echo get_msg('upload_photo') ?></label><br />
                    <input type="file" name="images1" id="fileInput" accept="image/*"><br />
					<!-- Add a <label> element for the error message with a unique ID -->
                    <label id="fileErrorLabel" style="color: red;"></label>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Add an event handler to the form submission -->
                <input type="submit" value="Upload" class="btn btn-sm btn-primary" onclick="return validateForm()">
                <a href='#' class="btn btn-sm btn-primary" data-dismiss="modal"><?php echo get_msg('btn_cancel') ?></a>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript function to validate the form before submission
    function validateForm() {
        // Get the file input element
        var fileInput = document.getElementById('fileInput');
        
        // Get the error label element
        var errorLabel = document.getElementById('fileErrorLabel');

        // Check if a file is selected
        if (fileInput.files.length === 0) {
            // Display an error message in the label
            errorLabel.textContent = 'Please choose a file.';
            return false; // Prevent form submission
        } else {
            // Clear the error message
            errorLabel.textContent = '';
        }

        // If a file is selected, allow the form to be submitted
        return true;
    }
</script>
