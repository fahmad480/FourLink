@extends('layouts.guest')

@section('title', 'Forgot Password - ' . $appName)

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3 fw-bold">
                            <i class="fas fa-key text-primary"></i> Forgot Password
                        </h1>
                        <p class="text-muted">Enter your email to reset password</p>
                    </div>

                    <form id="forgotPasswordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="forgotBtn">
                            <i class="fas fa-paper-plane"></i> Send Reset Link
                        </button>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#forgotPasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        const btn = $('#forgotBtn');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
        
        $.ajax({
            url: '{{ route("forgot-password.post") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Reset Link');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#forgotPasswordForm')[0].reset();
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Reset Link');
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}`).next('.invalid-feedback').text(value[0]);
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
