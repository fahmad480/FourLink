@extends('layouts.app')

@section('title', 'Dashboard - ' . $appName)

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

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-link fa-2x text-primary mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_link_groups'] }}</h3>
                    <p class="text-muted mb-0">Link Groups</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-boxes fa-2x text-success mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_components'] }}</h3>
                    <p class="text-muted mb-0">Components</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-eye fa-2x text-info mb-2"></i>
                    <h3 class="mb-0">{{ $stats['total_views'] }}</h3>
                    <p class="text-muted mb-0">Total Views</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-warning mb-2"></i>
                    <h3 class="mb-0">{{ $stats['active_links'] }}</h3>
                    <p class="text-muted mb-0">Active Links</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Views Over Time (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Top Link Groups</h5>
                </div>
                <div class="card-body">
                    <canvas id="topLinksChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Link Groups -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Link Groups</h5>
                </div>
                <div class="card-body">
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
                                <i class="fas fa-eye"></i> Manage
                            </a>
                            <a href="{{ public_route('public.show', $linkGroup->slug) }}" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-external-link-alt"></i>
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
                </div>
            </div>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
$(document).ready(function() {
    // Views Over Time Chart
    const viewsData = @json($viewsData);
    const viewsDates = viewsData.map(item => new Date(item.view_date).toLocaleDateString());
    const viewsCounts = viewsData.map(item => item.total_views);
    
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: viewsDates,
            datasets: [{
                label: 'Views',
                data: viewsCounts,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Top Link Groups Chart
    const topLinks = @json($topLinkGroups);
    const topTitles = topLinks.map(item => item.title.length > 20 ? item.title.substring(0, 20) + '...' : item.title);
    const topViews = topLinks.map(item => item.views_count);
    
    const topCtx = document.getElementById('topLinksChart').getContext('2d');
    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: topTitles,
            datasets: [{
                label: 'Views',
                data: topViews,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Delete link group functionality
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
