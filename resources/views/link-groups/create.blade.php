@extends('layouts.app')

@section('title', 'Create Link Group - ' . $appName)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus"></i> Create New Link Group
                    </h4>
                </div>
                <div class="card-body">
                    <form id="createLinkGroupForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="background_color" class="form-label">Background Color *</label>
                            <input type="color" class="form-control form-control-color" 
                                   id="background_color" name="background_color" value="#4f46e5" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" 
                                   name="thumbnail" accept="image/*">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Rectangle image like Google Form (Max: 2MB)</small>
                            <div id="thumbnailPreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Protection (Optional)</label>
                            <input type="password" class="form-control" id="password" 
                                   name="password" placeholder="Leave empty for public access">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Visitors will need this password to view the link group</small>
                        </div>

                        <div class="mb-3">
                            <h5>Social Media Links (Optional)</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="instagram_url" class="form-label">
                                        <i class="fab fa-instagram text-danger"></i> Instagram
                                    </label>
                                    <input type="url" class="form-control" id="instagram_url" 
                                           name="instagram_url" placeholder="https://instagram.com/username">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="facebook_url" class="form-label">
                                        <i class="fab fa-facebook text-primary"></i> Facebook
                                    </label>
                                    <input type="url" class="form-control" id="facebook_url" 
                                           name="facebook_url" placeholder="https://facebook.com/username">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="x_url" class="form-label">
                                        <i class="fab fa-x-twitter"></i> X (Twitter)
                                    </label>
                                    <input type="url" class="form-control" id="x_url" 
                                           name="x_url" placeholder="https://x.com/username">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="threads_url" class="form-label">
                                        <i class="fab fa-threads"></i> Threads
                                    </label>
                                    <input type="url" class="form-control" id="threads_url" 
                                           name="threads_url" placeholder="https://threads.net/@username">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12">
                                    <label for="website_url" class="form-label">
                                        <i class="fas fa-globe"></i> Website
                                    </label>
                                    <input type="url" class="form-control" id="website_url" 
                                           name="website_url" placeholder="https://yourwebsite.com">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" 
                                   name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active (visible to public)
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Create Link Group
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('components.cropper-modal')

@push('scripts')
<script>
$(document).ready(function() {
    let thumbnailFile = null;
    
    // Trigger cropper for thumbnail with fixed ratio (16:9)
    $('#thumbnail').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            thumbnailFile = file;
            // Initialize cropper with 16:9 aspect ratio
            initCropper(file, 'thumbnail', 16/9);
        }
    });
    
    // After cropping, show preview
    $('#thumbnail').on('change', function(e) {
        if (this.files && this.files[0] && !thumbnailFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnailPreview').html(`
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                `);
            };
            reader.readAsDataURL(this.files[0]);
        }
        thumbnailFile = null;
    });

    $('#createLinkGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("link-groups.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = response.redirect;
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Create Link Group');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'Something went wrong!'
                    });
                }
            }
        });
    });
});
</script>
@endpush
