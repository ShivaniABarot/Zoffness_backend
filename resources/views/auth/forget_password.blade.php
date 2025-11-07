<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Forgot Password</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('css/login-page-styles.css') }}">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Forgot Password Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bolder">zoffness College prep</span>
              </a>
            </div>
            <h4 class="mb-2">Reset Your Password</h4>
            <p class="mb-4">You will receive an email with a link to reset your password.</p>
            @if(session('success'))
              <div class="alert alert-success mb-3">
                {{ session('success') }}
              </div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger mb-3">
                {{ session('error') }}
              </div>
            @endif
            <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">

              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required
                  autofocus />
                @error('email')
                  <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <button type="submit" class="btn btn-success d-grid w-100">Send Password Reset Link</button>
              </div>
              <div class="text-center">
                <a href="{{ url('login') }}">
                  <small>Back to Login</small>
                </a>
              </div>
            </form>
          </div>
        </div>
        <!-- /Forgot Password Card -->
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function () {
      $('#forgot-password-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          url: "{{ route('password.email') }}",
          type: "POST",
          data: $(this).serialize(),
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          success: function (response) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: response.message || 'A password reset link has been sent to your email.',
              showConfirmButton: false,
              timer: 1500
            });
          },
          error: function (xhr) {
            console.log('Error:', xhr.responseJSON);

            let message = 'An error occurred. Please try again.';
            if (xhr.status === 422 && xhr.responseJSON?.message) {
              message = xhr.responseJSON.message; // validation or user not found
            }

            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: message,
              confirmButtonText: 'OK'
            });
          }

        });
      });
    });
  </script>
</body>

</html>