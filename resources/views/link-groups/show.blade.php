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
                                    @elseif($component->type === 'video' && $component->content)
                                        <div class="ratio ratio-16x9 mt-2" style="max-width: 500px;">
                                            <iframe src="https://www.youtube.com/embed/{{ $component->content }}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                        </div>
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

<!-- Edit Component Modal -->
<div class="modal fade" id="editComponentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editComponentForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_component_id" name="component_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Type *</label>
                        <select class="form-select" id="edit_type" name="type" required>
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
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3" id="editEmojiField">
                        <label for="edit_emoji" class="form-label">Emoji (Optional for Links)</label>
                        <input type="text" class="form-control" id="edit_emoji" name="emoji" placeholder="Pick an emoji 😊" maxlength="4">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Add an emoji to display instead of the default icon. Works best with link type.</small>
                    </div>

                    <div class="mb-3" id="editContentField">
                        <label for="edit_content" class="form-label" id="editContentLabel">Content</label>
                        <textarea class="form-control" id="edit_content" name="content" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted" id="editContentHelp"></small>
                    </div>

                    <div class="mb-3 d-none" id="editFileField">
                        <label for="edit_file" class="form-label" id="editFileLabel">File</label>
                        <div id="currentFilePreview" class="mb-2"></div>
                        <input type="file" class="form-control" id="edit_file" name="file">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted" id="editFileHelp">Leave empty to keep current file</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitEditComponentBtn">
                        <i class="fas fa-save"></i> Update Component
                    </button>
                </div>
            </form>
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

                    <div class="mb-3" id="emojiField">
                        <label for="emoji" class="form-label">Emoji (Optional for Links)</label>
                        <input type="text" class="form-control" id="emoji" name="emoji" placeholder="Pick an emoji 😊" maxlength="4">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted">Add an emoji to display instead of the default icon. Works best with link type.</small>
                    </div>

                    <div class="mb-3" id="contentField">
                        <label for="content" class="form-label" id="contentLabel">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted" id="contentHelp"></small>
                    </div>

                    <div class="mb-3 d-none" id="fileField">
                        <label for="file" class="form-label" id="fileLabel">File</label>
                        <input type="file" class="form-control" id="file" name="file">
                        <div class="invalid-feedback"></div>
                        <small class="text-muted" id="fileHelp"></small>
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

@include('components.cropper-modal')

@push('scripts')
<script>
$(document).ready(function() {
    let imageFile = null;
    
    // Handle file input change for image components - trigger cropper
    $('#file').on('change', function(e) {
        const file = e.target.files[0];
        const type = $('#type').val();
        
        if (file && type === 'image') {
            imageFile = file;
            // Initialize cropper with free aspect ratio
            initCropper(file, 'file');
        }
    });
    
    // Reset imageFile flag after cropping is done
    const originalFileChange = $('#file')[0].onchange;
    $('#file').on('change', function() {
        if (!imageFile) {
            // This is the change event after cropping, not the initial file selection
        }
        imageFile = null;
    });
    
    // Handle type change
    $('#type').on('change', function() {
        const type = $(this).val();
        
        $('#contentField, #fileField').addClass('d-none');
        $('#content, #file').attr('required', false);
        
        // Update labels and help text based on type
        if (type === 'link') {
            $('#contentField').removeClass('d-none');
            $('#content').attr('required', true);
            $('#contentLabel').text('Link URL *');
            $('#contentHelp').text('Enter the full URL (e.g., https://example.com)');
        } else if (type === 'text') {
            $('#contentField').removeClass('d-none');
            $('#content').attr('required', true);
            $('#contentLabel').text('Text Content *');
            $('#contentHelp').text('Enter your text content here');
        } else if (type === 'embed') {
            $('#contentField').removeClass('d-none');
            $('#content').attr('required', true);
            $('#contentLabel').text('Embed Code (iframe/widget) *');
            $('#contentHelp').text('Paste your embed code from YouTube, Twitter, etc.');
        } else if (type === 'video') {
            $('#contentField').removeClass('d-none');
            $('#content').attr('required', true);
            $('#contentLabel').text('YouTube Video ID *');
            $('#contentHelp').text('From https://youtube.com/watch?v=VIDEO_ID, use only VIDEO_ID (e.g., dQw4w9WgXcQ)');
        } else if (type === 'image') {
            $('#fileField').removeClass('d-none');
            $('#file').attr('required', true).attr('accept', 'image/*');
            $('#fileLabel').text('Image File *');
            $('#fileHelp').text('Upload an image file (JPG, PNG, GIF, etc.)');
        } else if (type === 'file') {
            $('#fileField').removeClass('d-none');
            $('#file').attr('required', true).attr('accept', '*');
            $('#fileLabel').text('File to Upload *');
            $('#fileHelp').text('Upload any file type (PDF, DOC, ZIP, etc.)');
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

    // Edit component - open modal and populate data
    $('.edit-component').on('click', function() {
        const componentId = $(this).data('id');
        
        // Fetch component data
        $.ajax({
            url: `/link-groups/{{ $linkGroup->slug }}/components/${componentId}/edit`,
            method: 'GET',
            success: function(response) {
                // Populate form fields
                $('#edit_component_id').val(response.component.id);
                $('#edit_type').val(response.component.type).trigger('change');
                $('#edit_title').val(response.component.title);
                $('#edit_emoji').val(response.component.emoji);
                $('#edit_content').val(response.component.content);
                
                // Show current file preview if exists
                if (response.component.file_path) {
                    let preview = '';
                    if (response.component.type === 'image') {
                        preview = `<img src="/storage/${response.component.file_path}" class="img-thumbnail" style="max-height: 100px;">`;
                    } else if (response.component.type === 'file') {
                        preview = `<p class="text-muted small">Current file: ${response.component.file_path.split('/').pop()}</p>`;
                    }
                    $('#currentFilePreview').html(preview);
                } else {
                    $('#currentFilePreview').html('');
                }
                
                // Show modal
                $('#editComponentModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load component data'
                });
            }
        });
    });

    // Handle edit type change (same logic as add)
    $('#edit_type').on('change', function() {
        const type = $(this).val();
        
        $('#editContentField, #editFileField').addClass('d-none');
        $('#edit_content, #edit_file').attr('required', false);
        
        if (type === 'link') {
            $('#editContentField').removeClass('d-none');
            $('#edit_content').attr('required', true);
            $('#editContentLabel').text('Link URL *');
            $('#editContentHelp').text('Enter the full URL (e.g., https://example.com)');
        } else if (type === 'text') {
            $('#editContentField').removeClass('d-none');
            $('#edit_content').attr('required', true);
            $('#editContentLabel').text('Text Content *');
            $('#editContentHelp').text('Enter your text content here');
        } else if (type === 'embed') {
            $('#editContentField').removeClass('d-none');
            $('#edit_content').attr('required', true);
            $('#editContentLabel').text('Embed Code (iframe/widget) *');
            $('#editContentHelp').text('Paste your embed code from YouTube, Twitter, etc.');
        } else if (type === 'video') {
            $('#editContentField').removeClass('d-none');
            $('#edit_content').attr('required', true);
            $('#editContentLabel').text('YouTube Video ID *');
            $('#editContentHelp').text('From https://youtube.com/watch?v=VIDEO_ID, use only VIDEO_ID (e.g., dQw4w9WgXcQ)');
        } else if (type === 'image') {
            $('#editFileField').removeClass('d-none');
            $('#edit_file').attr('accept', 'image/*');
            $('#editFileLabel').text('Image File');
            $('#editFileHelp').text('Leave empty to keep current image, or upload new image to replace');
        } else if (type === 'file') {
            $('#editFileField').removeClass('d-none');
            $('#edit_file').attr('accept', '*');
            $('#editFileLabel').text('File to Upload');
            $('#editFileHelp').text('Leave empty to keep current file, or upload new file to replace');
        }
    });

    // Handle edit form submission
    $('#editComponentForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#submitEditComponentBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        const componentId = $('#edit_component_id').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `/link-groups/{{ $linkGroup->slug }}/components/${componentId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editComponentModal').modal('hide');
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                location.reload();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Update Component');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`#edit_${key}`).addClass('is-invalid');
                        $(`#edit_${key}`).siblings('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON.message || 'Failed to update component!'
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
