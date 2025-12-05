@extends('layouts.app')

@section('title', $linkGroup->title . ' - FourLink')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Link Group Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="mb-2">{{ $linkGroup->title }}</h2>
                            <p class="text-muted mb-2">{{ $linkGroup->description }}</p>
                            <div class="d-flex gap-3 text-muted small">
                                <span><i class="fas fa-eye"></i> {{ $linkGroup->views_count }} views</span>
                                <span>
                                    <i class="fas fa-circle" style="color: {{ $linkGroup->background_color }}"></i>
                                    {{ $linkGroup->background_color }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('public.show', $linkGroup->slug) }}" 
                               class="btn btn-sm btn-info" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View Public
                            </a>
                            <a href="{{ route('link-groups.edit', $linkGroup->slug) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    
                    @if($linkGroup->thumbnail)
                        <img src="{{ asset('storage/' . $linkGroup->thumbnail) }}" 
                             class="img-fluid rounded" 
                             alt="{{ $linkGroup->title }}">
                    @endif
                </div>
            </div>

            <!-- Add Component Button -->
            <div class="card mb-4">
                <div class="card-body">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addComponentModal">
                        <i class="fas fa-plus"></i> Add Component
                    </button>
                </div>
            </div>

            <!-- Components List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Components ({{ $linkGroup->components->count() }})</h5>
                </div>
                <div class="card-body" id="componentsList">
                    @forelse($linkGroup->components as $component)
                        <div class="component-item border rounded p-3 mb-2" data-id="{{ $component->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas {{ $component->icon }} me-2"></i>
                                        <span class="badge bg-secondary">{{ ucfirst($component->type) }}</span>
                                        @if($component->title)
                                            <strong class="ms-2">{{ $component->title }}</strong>
                                        @endif
                                    </div>
                                    
                                    @if($component->type === 'link')
                                        <a href="{{ $component->content }}" target="_blank" class="text-decoration-none">
                                            {{ $component->content }}
                                        </a>
                                    @elseif($component->type === 'text')
                                        <p class="mb-0">{{ $component->content }}</p>
                                    @elseif($component->type === 'image' && $component->file_path)
                                        <img src="{{ asset('storage/' . $component->file_path) }}" 
                                             class="img-fluid rounded mt-2" style="max-height: 200px;">
                                    @elseif($component->type === 'video' && $component->file_path)
                                        <video controls class="w-100 mt-2" style="max-height: 300px;">
                                            <source src="{{ asset('storage/' . $component->file_path) }}">
                                        </video>
                                    @elseif($component->type === 'file' && $component->file_path)
                                        <a href="{{ asset('storage/' . $component->file_path) }}" 
                                           class="btn btn-sm btn-outline-primary mt-2" download>
                                            <i class="fas fa-download"></i> Download File
                                        </a>
                                    @elseif($component->type === 'embed')
                                        <div class="mt-2">{!! $component->content !!}</div>
                                    @endif
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-warning edit-component" 
                                            data-id="{{ $component->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-component" 
                                            data-id="{{ $component->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted py-4">
                            No components yet. Add your first component!
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Component Modal -->
<div class="modal fade" id="addComponentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addComponentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="link">Link</option>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="file">File</option>
                            <option value="embed">Embed Code</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3" id="contentField">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3 d-none" id="fileField">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file" name="file">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitComponentBtn">
                        <i class="fas fa-save"></i> Add Component
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle type change
    $('#type').on('change', function() {
        const type = $(this).val();
        
        $('#contentField, #fileField').addClass('d-none');
        
        if (type === 'link' || type === 'text' || type === 'embed') {
            $('#contentField').removeClass('d-none');
            $('#content').attr('required', true);
        } else if (type === 'image' || type === 'video' || type === 'file') {
            $('#fileField').removeClass('d-none');
            $('#file').attr('required', true);
        }
    });

    // Add component
    $('#addComponentForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitComponentBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("components.store", $linkGroup->slug) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                location.reload();
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

    // Delete component
    $('.delete-component').on('click', function() {
        const componentId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/link-groups/{{ $linkGroup->slug }}/components/${componentId}`,
                    method: 'DELETE',
                    success: function(response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to delete!'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
