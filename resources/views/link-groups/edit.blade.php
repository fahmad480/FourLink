@extends('layouts.app')

@section('title', 'Edit Link Group - FourLink')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Link Group
                    </h4>
                </div>
                <div class="card-body">
                    <form id="editLinkGroupForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ $linkGroup->title }}" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $linkGroup->description }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="background_color" class="form-label">Background Color *</label>
                            <input type="color" class="form-control form-control-color" 
                                   id="background_color" name="background_color" 
                                   value="{{ $linkGroup->background_color }}" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            @if($linkGroup->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $linkGroup->thumbnail) }}" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="thumbnail" 
                                   name="thumbnail" accept="image/*">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Leave empty to keep current thumbnail</small>
                            <div id="thumbnailPreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" 
                                   name="is_active" value="1" {{ $linkGroup->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible to public)
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Update Link Group
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
    // Preview thumbnail
    $('#thumbnail').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnailPreview').html(`
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">
                `);
            };
            reader.readAsDataURL(file);
        }
    });

    $('#editLinkGroupForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("link-groups.update", $linkGroup->slug) }}',
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
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Link Group');
                
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
