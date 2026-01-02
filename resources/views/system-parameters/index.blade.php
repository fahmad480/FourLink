@extends('layouts.app')

@section('title', 'System Parameters')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-cog"></i> System Parameters
                </h2>
                <a href="{{ route('admin.system-parameters.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create New
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Value</th>
                                    <th>Description</th>
                                    <th>Updated At</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parameters as $parameter)
                                    <tr>
                                        <td>
                                            <code>{{ $parameter->code }}</code>
                                        </td>
                                        <td>
                                            @if(is_null($parameter->value))
                                                <span class="badge bg-secondary">NULL</span>
                                            @else
                                                <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $parameter->value }}">
                                                    {{ $parameter->value }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($parameter->description, 50) }}</td>
                                        <td>{{ $parameter->updated_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.system-parameters.edit', $parameter) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!is_null($parameter->value))
                                                    <form action="{{ route('admin.system-parameters.set-null', $parameter) }}" 
                                                          method="POST" 
                                                          class="d-inline set-null-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info" title="Set to NULL">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.system-parameters.destroy', $parameter) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No system parameters found</p>
                                            <a href="{{ route('admin.system-parameters.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create First Parameter
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($parameters->hasPages())
                        <div class="mt-3">
                            {{ $parameters->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        if (confirm('Are you sure you want to delete this system parameter?')) {
            form.submit();
        }
    });

    // Set null confirmation
    $('.set-null-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        
        if (confirm('Are you sure you want to set this parameter value to NULL?')) {
            form.submit();
        }
    });
});
</script>
@endpush
