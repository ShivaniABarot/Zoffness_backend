<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Create an Account - Zoffness</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('css/register-page-styles.css') }}" />

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
        <!-- Register Card -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="{{ url('/') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <!-- You can replace this with your own logo SVG -->
                  <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                      <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                      <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                      <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                      <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                          <g id="Mask" transform="translate(0.000000, 8.000000)">
                            <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                            </mask>
                            <use fill="#696cff" xlink:href="#path-1"></use>
                            <g id="Path-3" mask="url(#mask-2)">
                              <use fill="#696cff" xlink:href="#path-3"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                            </g>
                            <g id="Path-4" mask="url(#mask-2)">
                              <use fill="#696cff" xlink:href="#path-4"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                            </g>
                          </g>
                          <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                            <use fill="#696cff" xlink:href="#path-5"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                          </g>
                        </g>
                      </g>
                    </g>
                  </svg>
                </span>
                <span class="app-brand-text demo text-body fw-bolder">Zoffness</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2 text-center">Create an Account 🚀</h4>
            <p class="mb-4 text-center">Join us and start your adventure!</p>

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

            <form id="registerForm" class="mb-3" method="POST" action="{{ route('register') }}">
              @csrf

              <!-- Name Field -->
              <div class="mb-3">
                <label for="username" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Enter your name" value="{{ old('username') }}" autofocus required />
                <div class="text-danger mt-1" id="usernameError">@error('username') {{ $message }} @enderror</div>
              </div>

              <!-- Email Field -->
              <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter your email" value="{{ old('email') }}" required />
                <div class="text-danger mt-1" id="emailError">@error('email') {{ $message }} @enderror</div>
              </div>

              <!-- Password Field -->
              <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter your password" required />
                  <span class="password-toggle-icon" id="togglePassword"><i class="fa fa-eye-slash"></i></span>
                </div>
                <div class="text-danger mt-1" id="passwordError">@error('password') {{ $message }} @enderror</div>
                <small class="form-text text-muted">Password must be at least 8 characters and include uppercase, lowercase, and numbers.</small>
              </div>

              <!-- Confirm Password Field -->
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Re-enter your password" required />
                  <span class="password-toggle-icon" id="togglePasswordConfirm"><i class="fa fa-eye-slash"></i></span>
                </div>
                <div class="text-danger mt-1" id="passwordConfirmationError">@error('password_confirmation') {{ $message }} @enderror</div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
              </div>

              <p class="text-center">
                <span>Already have an account?</span>
                <a href="{{ url('login') }}">
                  <span>Sign in</span>
                </a>
              </p>
            </form>
          </div>
        </div>
        <!-- /Register Card -->
      </div>
    </div>
  </div>
  <!-- / Content -->

  <!-- Core JS -->
  <script>
    $(document).ready(function() {
        console.log('Document ready');

        // Also add a direct click handler to the submit button
        $('.btn-primary').on('click', function(e) {
            console.log('Button clicked directly');
            // Let the form submit handler handle the actual submission
        });

        // Password toggle functionality
        $('.password-toggle-icon').on('click', function() {
            const passwordInput = $(this).siblings('input');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        // We're now using traditional form submission, but keeping the AJAX code commented
        // for reference in case you want to switch back

        /*
        $('#registerForm').on('submit', function(e) {
            // This is now handled by the traditional form submission
            // The form will submit directly to the server without AJAX

            // Uncomment this section if you want to use AJAX again

            e.preventDefault();
            console.log('Form submitted via AJAX');

            // For debugging - show all form data
            let formData = new FormData(this);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // Clear previous errors
            $('#usernameError, #emailError, #passwordError, #passwordConfirmationError').text('');

            // Add CSRF token to headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            console.log('Making AJAX call to:', "{{ route('register') }}");

            // Ajax call
            $.ajax({
                url: "{{ route('register') }}",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            window.location.href = "{{ route('login') }}";
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error status:', status);
                    console.log('Error message:', error);
                    console.log('Full XHR response:', xhr);

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        console.log('Validation errors:', errors);

                        if (errors.username) {
                            $('#usernameError').text(errors.username[0]);
                        }
                        if (errors.email) {
                            $('#emailError').text(errors.email[0]);
                        }
                        if (errors.password) {
                            $('#passwordError').text(errors.password[0]);
                        }
                        if (errors.password_confirmation) {
                            $('#passwordConfirmationError').text(errors.password_confirmation[0]);
                        }
                    } else {
                        // Show a general error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: 'There was a problem with your registration. Please try again.'
                        });
                    }
                }
            });
        });
        */
    });
  </script>
</body>
</html>
