@extends('layouts.app')

@section('title', 'Create Component - FourLink')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus"></i> Add Component to "{{ $linkGroup->title }}"
                    </h4>
                </div>
                <div class="card-body">
                    <form id="createComponentForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="link">Link - External URL</option>
                                <option value="text">Text - Plain text content</option>
                                <option value="image">Image - Upload image file</option>
                                <option value="video">Video - Upload video file</option>
                                <option value="file">File - Upload document/file</option>
                                <option value="embed">Embed - Embed code (iframe, widget)</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Optional: Display title for this component</small>
                        </div>

                        <div class="mb-3" id="contentField" style="display: none;">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="4"></textarea>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted" id="contentHelp"></small>
                        </div>

                        <div class="mb-3" id="fileField" style="display: none;">
                            <label for="file" class="form-label">File *</label>
                            <input type="file" class="form-control" id="file" name="file" data-skip-cropper="true">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted" id="fileHelp"></small>
                            <div id="filePreview" class="mt-2"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Add Component
                            </button>
                            <a href="{{ route('link-groups.show', $linkGroup->slug) }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#type').on('change', function() {
        const type = $(this).val();
        
        // Hide all fields
        $('#contentField, #fileField').hide();
        $('#content, #file').removeAttr('required');
        
        // Show relevant field based on type
        if (type === 'link') {
            $('#contentField').show();
            $('#content').attr('required', true);
            $('#contentHelp').text('Enter the full URL (e.g., https://example.com)');
        } else if (type === 'text') {
            $('#contentField').show();
            $('#content').attr('required', true);
            $('#contentHelp').text('Enter your text content');
        } else if (type === 'embed') {
            $('#contentField').show();
            $('#content').attr('required', true);
            $('#contentHelp').text('Paste embed code (iframe, widget code, etc.)');
        } else if (type === 'image') {
            $('#fileField').show();
            $('#file').attr('required', true).attr('accept', 'image/*');
            $('#fileHelp').text('Upload image file (JPG, PNG, GIF - Max: 10MB)');
        } else if (type === 'video') {
            $('#fileField').show();
            $('#file').attr('required', true).attr('accept', 'video/*');
            $('#fileHelp').text('Upload video file (MP4, AVI, MOV - Max: 10MB)');
        } else if (type === 'file') {
            $('#fileField').show();
            $('#file').attr('required', true).attr('accept', '*');
            $('#fileHelp').text('Upload any file type (Max: 10MB)');
        }
    });

    // Preview file
    $('#file').on('change', function(e) {
        const file = e.target.files[0];
        const type = $('#type').val();
        
        if (file && type === 'image') {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#filePreview').html(`
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                `);
            };
            reader.readAsDataURL(file);
        } else if (file) {
            $('#filePreview').html(`
                <div class="alert alert-info">
                    <i class="fas fa-file"></i> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                </div>
            `);
        }
    });

    $('#createComponentForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("components.store", $linkGroup->slug) }}',
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
                    window.location.href = '{{ route("link-groups.show", $linkGroup->slug) }}';
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Add Component');
                
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
