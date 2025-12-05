@extends('layouts.app')

@section('title', 'Dashboard - FourLink')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-home"></i> Dashboard
                </h2>
                <a href="{{ route('link-groups.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New Link Group
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($linkGroups as $linkGroup)
            <div class="col-md-6 col-lg-4">
                <div class="card link-group-card h-100">
                    @if($linkGroup->thumbnail)
                        <img src="{{ asset('storage/' . $linkGroup->thumbnail) }}" 
                             class="card-img-top" 
                             alt="{{ $linkGroup->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center" 
                             style="height: 200px; background: {{ $linkGroup->background_color }};">
                            <i class="fas fa-link fa-3x text-white"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $linkGroup->title }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($linkGroup->description, 100) }}
                        </p>
                        
                        <div class="d-flex justify-content-between text-muted small mb-3">
                            <span><i class="fas fa-boxes"></i> {{ $linkGroup->components_count }} components</span>
                            <span><i class="fas fa-eye"></i> {{ $linkGroup->views_count }} views</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('link-groups.show', $linkGroup->slug) }}" 
                               class="btn btn-sm btn-primary flex-fill">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('link-groups.edit', $linkGroup->slug) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger delete-link-group" 
                                    data-slug="{{ $linkGroup->slug }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <small class="text-muted">
                                Public URL: 
                                <a href="{{ route('public.show', $linkGroup->slug) }}" 
                                   target="_blank" 
                                   class="text-decoration-none">
                                    {{ route('public.show', $linkGroup->slug) }}
                                </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                        <h4>No Link Groups Yet</h4>
                        <p class="text-muted">Create your first link group to get started!</p>
                        <a href="{{ route('link-groups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Link Group
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($linkGroups->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $linkGroups->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-link-group').on('click', function() {
        const slug = $(this).data('slug');
        
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
                    url: `/link-groups/${slug}`,
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
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
