<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - Zoffness Collage Prep</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('css/login-page-styles.css') }}" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!-- Content -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Login Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <!-- Your SVG logo (unchanged) -->
                  <!-- ... -->
                </span>
                <span class="app-brand-text demo text-body fw-bolder">Zoffness</span>
              </a>
            </div>

            <h4 class="mb-2">Welcome! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

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

            <!-- Social Login -->
            <div class="mb-3 text-center">
              <p class="mb-2">Or sign in with</p>
              <a href="{{ url('/login/google') }}" class="btn btn-outline-danger w-100 mb-2">
  <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo" style="width: 18px; height: 18px; margin-right: 8px;"> Sign in with Google
</a>

              <!-- <a href="{{ url('/login/facebook') }}" class="btn btn-outline-primary w-100">
                <i class="fab fa-facebook-f me-2"></i> Sign in with Facebook
              </a> -->
            </div>

            <!-- Login Form -->
            <form id="login-form" class="mb-3">
              <div class="mb-3">
                <label for="email" class="form-label">Email or Username</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                <div class="text-danger mt-1" id="email-error" style="display: none;">Email is required</div>
              </div>

              <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                  <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••" />
                  <span class="password-toggle-icon"><i class="fa fa-eye-slash"></i></span>
                </div>
                <div class="text-danger mt-1" id="password-error" style="display: none;">Password is required</div>
                <div class="mt-1 text-end">
                  <a href="{{ url('forgot_password') }}">
                    <small>Forgot Password?</small>
                  </a>
                </div>
              </div>

              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" />
                  <label class="form-check-label" for="remember"> Remember Me </label>
                </div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </form>

            <p class="text-center">
              <span>New on our platform?</span>
              <a href="{{ url('register') }}">
                <span>Create an account</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Login Card -->
      </div>
    </div>
  </div>
  <!-- / Content -->

  <!-- Core JS -->
  <script>
    $(document).ready(function() {
        // Toggle Password
        $('.password-toggle-icon').on('click', function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        // Submit Login
        $('#login-form').on('submit', function(e) {
            e.preventDefault();
            $('#email-error').hide();
            $('#password-error').hide();

            let hasError = false;
            if ($('#email').val().trim() === '') {
                $('#email-error').show();
                hasError = true;
            }
            if ($('#password').val().trim() === '') {
                $('#password-error').show();
                hasError = true;
            }

            if (hasError) return;

            $.ajax({
                url: "{{ url('/login') }}",
                type: "POST",
                data: {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    remember: $('#remember').is(':checked')
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        if (response.success) {
                            window.location.href = "{{ url('/dashboard') }}";
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred.',
                        confirmButtonText: 'OK'
                    });
                },
            });
        });
    });
  </script>
</body>
</html>
