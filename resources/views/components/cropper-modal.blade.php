<!-- Image Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop" src="" alt="Image to crop" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropImageBtn">
                    <i class="fas fa-crop"></i> Crop & Save
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
let cropper = null;
let currentCropTarget = null;
let cropAspectRatio = NaN; // Free aspect ratio by default

window.initCropper = function(file, targetInputId, aspectRatio = NaN) {
    currentCropTarget = targetInputId;
    cropAspectRatio = aspectRatio;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('imageToCrop');
        image.src = e.target.result;
        
        // Show modal
        const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
        cropperModal.show();
        
        // Wait for modal to be fully shown
        document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
            // Destroy previous cropper instance if exists
            if (cropper) {
                cropper.destroy();
            }
            
            // Initialize Cropper
            cropper = new Cropper(image, {
                aspectRatio: cropAspectRatio,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }, { once: true });
    };
    reader.readAsDataURL(file);
};

document.getElementById('cropImageBtn').addEventListener('click', function() {
    if (!cropper || !currentCropTarget) return;
    
    // Get cropped canvas
    const canvas = cropper.getCroppedCanvas({
        maxWidth: 2000,
        maxHeight: 2000,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    // Convert canvas to blob
    canvas.toBlob(function(blob) {
        // Create a new file from blob
        const file = new File([blob], 'cropped-image.jpg', { type: 'image/jpeg' });
        
        // Create a DataTransfer object to set the file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        
        // Set the file to the target input
        const targetInput = document.getElementById(currentCropTarget);
        targetInput.files = dataTransfer.files;
        
        // Trigger change event to show preview
        targetInput.dispatchEvent(new Event('change', { bubbles: true }));
        
        // Hide modal
        bootstrap.Modal.getInstance(document.getElementById('cropperModal')).hide();
        
        // Destroy cropper
        cropper.destroy();
        cropper = null;
    }, 'image/jpeg', 0.9);
});

// Clean up on modal hidden
document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});
</script>
