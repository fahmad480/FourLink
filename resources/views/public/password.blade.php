<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Password Protected - {{ $linkGroup->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, {{ $linkGroup->background_color }} 0%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .password-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .password-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .lock-icon {
            font-size: 60px;
            color: {{ $linkGroup->background_color }};
            margin-bottom: 20px;
        }
        .password-title {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .password-subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .password-input {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .password-input:focus {
            border-color: {{ $linkGroup->background_color }};
            box-shadow: 0 0 0 0.2rem {{ $linkGroup->background_color }}33;
        }
        .btn-verify {
            background: {{ $linkGroup->background_color }};
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px {{ $linkGroup->background_color }}66;
        }
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }
        .error-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="password-card">
            <i class="fas fa-lock lock-icon"></i>
            <h1 class="password-title">{{ $linkGroup->title }}</h1>
            <p class="password-subtitle">This link group is password protected. Please enter the password to continue.</p>
            
            <form id="passwordForm">
                @csrf
                <div class="mb-3">
                    <input type="password" 
                           class="form-control password-input" 
                           id="password" 
                           name="password" 
                           placeholder="Enter password" 
                           required 
                           autofocus>
                    <div class="error-message" id="errorMessage"></div>
                </div>
                
                <button type="submit" class="btn btn-verify" id="submitBtn">
                    <i class="fas fa-unlock"></i> Verify Password
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.3/dist/sweetalert2.all.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault();
                
                const btn = $('#submitBtn');
                const errorMsg = $('#errorMessage');
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
                errorMsg.removeClass('show').text('');
                
                $.ajax({
                    url: '{{ route("public.verify", $linkGroup->slug) }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Access Granted!',
                            text: 'Redirecting...',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="fas fa-unlock"></i> Verify Password');
                        
                        if (xhr.status === 422) {
                            const message = xhr.responseJSON.message || 'Incorrect password. Please try again.';
                            errorMsg.text(message).addClass('show');
                            $('#password').val('').focus();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
