@extends('layouts.app')

@section('title', 'Admin Dashboard - FourLink')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-cog"></i> Admin Dashboard</h2>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Link Groups</h6>
                            <h2 class="mb-0">{{ $totalLinkGroups }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-folder fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Views</h6>
                            <h2 class="mb-0">{{ $totalViews }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-eye fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentUsers as $user)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                    <span class="badge bg-primary">
                                        {{ $user->roles->first()->name ?? 'No Role' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">
                            View All Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Link Groups</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentLinkGroups as $linkGroup)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $linkGroup->title }}</h6>
                                        <small class="text-muted">
                                            by {{ $linkGroup->user->name }} • 
                                            {{ $linkGroup->views_count }} views
                                        </small>
                                    </div>
                                    <a href="{{ route('link-groups.show', $linkGroup->slug) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('admin.link-groups') }}" class="btn btn-sm btn-outline-primary">
                            View All Link Groups
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
