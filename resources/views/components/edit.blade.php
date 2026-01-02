@extends('layouts.app')

@section('title', 'Edit Component - FourLink')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Component
                    </h4>
                </div>
                <div class="card-body">
                    <form id="editComponentForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="link" {{ $component->type === 'link' ? 'selected' : '' }}>Link - External URL</option>
                                <option value="text" {{ $component->type === 'text' ? 'selected' : '' }}>Text - Plain text content</option>
                                <option value="image" {{ $component->type === 'image' ? 'selected' : '' }}>Image - Upload image file</option>
                                <option value="video" {{ $component->type === 'video' ? 'selected' : '' }}>Video - Upload video file</option>
                                <option value="file" {{ $component->type === 'file' ? 'selected' : '' }}>File - Upload document/file</option>
                                <option value="embed" {{ $component->type === 'embed' ? 'selected' : '' }}>Embed - Embed code</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $component->title }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3" id="contentField">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="4">{{ $component->content }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3" id="fileField">
                            <label for="file" class="form-label">File</label>
                            
                            @if($component->file_path)
                                <div class="mb-2">
                                    @if($component->type === 'image')
                                        <img src="{{ asset('storage/' . $component->file_path) }}" 
                                             class="img-thumbnail" style="max-height: 200px;">
                                    @elseif($component->type === 'video')
                                        <video controls class="w-100" style="max-height: 300px;">
                                            <source src="{{ asset('storage/' . $component->file_path) }}">
                                        </video>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-file"></i> Current file: {{ basename($component->file_path) }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <input type="file" class="form-control" id="file" name="file" data-skip-cropper="true">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Leave empty to keep current file</small>
                            <div id="filePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" 
                                   name="is_active" value="1" {{ $component->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible in public view)
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Update Component
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
    function updateFields() {
        const type = $('#type').val();
        
        $('#contentField, #fileField').hide();
        
        if (type === 'link' || type === 'text' || type === 'embed') {
            $('#contentField').show();
        } else if (type === 'image' || type === 'video' || type === 'file') {
            $('#fileField').show();
        }
    }
    
    // Initialize on page load
    updateFields();
    
    $('#type').on('change', updateFields);

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

    $('#editComponentForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("components.update", [$linkGroup->slug, $component->id]) }}',
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
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Component');
                
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
