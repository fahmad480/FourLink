@extends('layouts.app')

@section('title', 'All Link Groups - Admin')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-folder"></i> All Link Groups</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Owner</th>
                            <th>Components</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linkGroups as $linkGroup)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($linkGroup->thumbnail)
                                            <img src="{{ asset('storage/' . $linkGroup->thumbnail) }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; background: {{ $linkGroup->background_color }};">
                                                <i class="fas fa-link text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $linkGroup->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $linkGroup->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $linkGroup->user->name }}</td>
                                <td>{{ $linkGroup->components_count }}</td>
                                <td>{{ $linkGroup->views_count }}</td>
                                <td>
                                    <span class="badge bg-{{ $linkGroup->is_active ? 'success' : 'secondary' }}">
                                        {{ $linkGroup->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $linkGroup->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('link-groups.show', $linkGroup->slug) }}" 
                                           class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ public_route('public.show', $linkGroup->slug) }}" 
                                           class="btn btn-info" target="_blank">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($linkGroups->hasPages())
                <div class="mt-3">
                    {{ $linkGroups->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
