@extends('layouts.app')

@section('title', 'Create System Parameter')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus"></i> Create New System Parameter
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.system-parameters.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Code *</label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}" 
                                   required
                                   placeholder="e.g., app_name">
                            <small class="text-muted">Unique identifier for this parameter (use lowercase and underscores)</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">Value</label>
                            <textarea class="form-control @error('value') is-invalid @enderror" 
                                      id="value" 
                                      name="value" 
                                      rows="3"
                                      placeholder="Enter parameter value (can be left empty)">{{ old('value') }}</textarea>
                            <small class="text-muted">Leave empty or check "Set as NULL" below for NULL value</small>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="set_null" 
                                       name="set_null"
                                       {{ old('set_null') ? 'checked' : '' }}>
                                <label class="form-check-label" for="set_null">
                                    Set value as NULL
                                </label>
                            </div>
                            <small class="text-muted">Check this to explicitly set the value to NULL (ignores value field)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="2"
                                      placeholder="Brief description of what this parameter does">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Parameter
                            </button>
                            <a href="{{ route('admin.system-parameters.index') }}" class="btn btn-secondary">
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
    // Handle set_null checkbox
    $('#set_null').on('change', function() {
        if ($(this).is(':checked')) {
            $('#value').prop('disabled', true).addClass('bg-light');
        } else {
            $('#value').prop('disabled', false).removeClass('bg-light');
        }
    });

    // Initialize on page load
    if ($('#set_null').is(':checked')) {
        $('#value').prop('disabled', true).addClass('bg-light');
    }
});
</script>
@endpush
