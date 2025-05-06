<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>@yield('title', 'Dashboard')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons - Use your preferred icon library -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!-- Core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-variables.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-utilities.css') }}" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Force sidebar to be visible and properly sized */
    .layout-menu {
      width: 260px !important;
      position: fixed !important;
      top: 0 !important;
      bottom: 0 !important;
      left: 0 !important;
      z-index: 1000 !important;
      overflow-y: auto !important;
      background-color: #fff !important;
      box-shadow: 0 0.125rem 0.375rem 0 rgba(161, 172, 184, 0.12) !important;
      display: block !important;
      visibility: visible !important;
      transform: none !important;
      opacity: 1 !important;
    }

    /* Force content to be properly positioned */
    .layout-page {
      margin-left: 260px !important;
      width: calc(100% - 260px) !important;
      position: relative !important;
      display: block !important;
    }

    /* Dashboard header styling - transparent */
    .d-flex.justify-content-end.align-items-center {
      background: transparent;
      box-shadow: none;
      margin-bottom: 0 !important;
    }

    /* Content wrapper adjustments */
    .content-wrapper {
      padding-top: 0 !important;
      margin-top: 0 !important;
    }

    .container-xxl {
      padding-top: 0 !important;
      padding-bottom: 0 !important;
    }

    /* Remove extra spacing */
    .container-p-y {
      padding-top: 0 !important;
      padding-bottom: 1.5rem !important;
    }

    /* Mobile adjustments */
    @media (max-width: 1199.98px) {
      .layout-menu {
        margin-left: -260px !important;
        transition: margin-left 0.3s ease-in-out !important;
      }

      .layout-page {
        margin-left: 0 !important;
        width: 100% !important;
      }

      body.layout-menu-expanded .layout-menu {
        margin-left: 0 !important;
      }
    }

    /* Force menu items to be properly displayed */
    .menu-inner {
      padding-top: 0 !important;
      padding-bottom: 2rem !important;
      padding-left: 0 !important;
      display: block !important;
      visibility: visible !important;
    }

    /* Force menu items to take full width */
    .menu-item {
      margin: 3px 0 !important;
      width: 100% !important;
      position: relative !important;
      display: block !important;
      visibility: visible !important;
    }

    /* Force menu links to be properly displayed */
    .menu-link {
      border-radius: 5px !important;
      margin: 0 8px !important;
      display: flex !important;
      align-items: center !important;
      padding: 0.6rem 1rem !important;
      width: calc(100% - 16px) !important;
      position: relative !important;
      left: 0 !important;
      z-index: 1 !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    /* Force menu icons to be properly aligned */
    .menu-icon {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 20px !important;
      height: 20px !important;
      margin-right: 12px !important;
      flex-shrink: 0 !important;
      position: relative !important;
      left: 0 !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    /* Force menu text to be visible */
    .menu-link div {
      line-height: 20px !important;
      display: block !important;
      visibility: visible !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      opacity: 1 !important;
      position: static !important;
      width: auto !important;
      height: auto !important;
      transform: none !important;
      margin-left: 0 !important;
    }

    /* Force menu text to be visible */
    .menu-text {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
      position: static !important;
      width: auto !important;
      height: auto !important;
      transform: none !important;
    }

    /* Force app brand text to be visible */
    .app-brand-text {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
      position: static !important;
      width: auto !important;
      height: auto !important;
      transform: none !important;
    }

    /* Hover effect */
    .menu-link:hover {
      background-color: rgba(105, 108, 255, 0.08) !important;
      color: #696cff !important;
      transform: translateX(5px) !important;
    }

    /* Fix for collapsed menu */
    .layout-menu-collapsed .menu-text,
    .layout-menu-collapsed .app-brand-text,
    .layout-menu-collapsed .menu-header {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    /* Fix for menu toggle */
    .layout-menu-toggle {
      display: none !important;
    }

    /* Force body to show expanded menu */
    body {
      overflow-x: hidden !important;
    }

    /* Force layout wrapper to be properly sized */
    .layout-wrapper {
      display: flex !important;
      width: 100% !important;
      align-items: stretch !important;
      flex: 1 1 auto !important;
    }

    /* Force layout container to be properly sized */
    .layout-container {
      display: flex !important;
      width: 100% !important;
      flex-direction: row !important;
      min-height: 100vh !important;
    }

    /* Force content wrapper to be properly sized */
    .content-wrapper {
      flex: 1 !important;
      width: 100% !important;
      display: flex !important;
      flex-direction: column !important;
    }

    /* Force container to be properly sized */
    .container-xxl {
      width: 100% !important;
      padding-right: 1.5rem !important;
      padding-left: 1.5rem !important;
      margin-right: auto !important;
      margin-left: auto !important;
      max-width: 1440px !important;
    }

    /* Force content padding */
    .container-p-y {
      padding-top: 1.625rem !important;
      padding-bottom: 1.625rem !important;
    }
  </style>
</head>

<body class="layout-menu-expanded">
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="width: 260px !important; position: fixed !important; top: 0 !important; bottom: 0 !important; left: 0 !important; z-index: 1000 !important; overflow-y: auto !important; background-color: #fff !important; box-shadow: 0 0.125rem 0.375rem 0 rgba(161, 172, 184, 0.12) !important; display: block !important; visibility: visible !important; transform: none !important; opacity: 1 !important;">
        <div class="app-brand demo">
          <a href="{{ route('dashboard') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              <!-- Your logo SVG or image here -->
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
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Zoffness</span>
          </a>
        </div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link" style="display: flex !important; align-items: center !important; padding: 0.6rem 1rem !important; width: calc(100% - 16px) !important; position: relative !important; left: 0 !important; z-index: 1 !important; visibility: visible !important; opacity: 1 !important;">
              <i class="menu-icon bx bx-home-circle" style="display: flex !important; align-items: center !important; justify-content: center !important; width: 20px !important; height: 20px !important; margin-right: 12px !important; flex-shrink: 0 !important; position: relative !important; left: 0 !important; visibility: visible !important; opacity: 1 !important;"></i>
              <div style="display: block !important; visibility: visible !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; opacity: 1 !important; position: static !important; width: auto !important; height: auto !important; transform: none !important; margin-left: 0 !important;">Dashboard</div>
            </a>
          </li>

          <!-- Menu Items -->
          <li class="menu-item {{ request()->is('users') ? 'active' : '' }}">
            <a href="{{ route('users') }}" class="menu-link">
              <i class="menu-icon bx bx-user"></i>
              <div>Users</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('tutors') ? 'active' : '' }}">
            <a href="{{ url('tutors') }}" class="menu-link">
              <i class="menu-icon bx bx-user-voice"></i>
              <div>Tutors</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('student') ? 'active' : '' }}">
            <a href="{{ url('student') }}" class="menu-link">
              <i class="menu-icon bx bx-user-pin"></i>
              <div>Students</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('session') ? 'active' : '' }}">
            <a href="{{ url('session') }}" class="menu-link">
              <i class="menu-icon bx bx-time"></i>
              <div>Sessions</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('timeslot') ? 'active' : '' }}">
            <a href="{{ url('timeslot') }}" class="menu-link">
              <i class="menu-icon bx bx-calendar"></i>
              <div>Timeslots</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('package') ? 'active' : '' }}">
            <a href="{{ url('package') }}" class="menu-link">
              <i class="menu-icon bx bx-package"></i>
              <div>Packages</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('payments') ? 'active' : '' }}">
            <a href="{{ url('payments') }}" class="menu-link">
              <i class="menu-icon bx bx-dollar"></i>
              <div>Payments</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('bookings') ? 'active' : '' }}">
            <a href="{{ url('bookings') }}" class="menu-link">
              <i class="menu-icon bx bx-book"></i>
              <div>Bookings</div>
            </a>
          </li>

          <!-- Inquiry Dropdown -->
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon bx bx-help-circle"></i>
              <div>Inquiries</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ Request::routeIs('enroll.list') ? 'active' : '' }}">
                <a href="{{ route('enroll.list') }}" class="menu-link">
                  <div>Enroll/Register</div>
                </a>
              </li>
              <li class="menu-item {{ Request::routeIs('pratice_test') ? 'active' : '' }}">
                <a href="{{ route('pratice_test') }}" class="menu-link">
                  <div>Practice Test & Analysis</div>
                </a>
              </li>
              <li class="menu-item {{ Request::routeIs('college_admission') ? 'active' : '' }}">
                <a href="{{ route('college_admission') }}" class="menu-link">
                  <div>College Admission Counseling</div>
                </a>
              </li>
              <li class="menu-item {{ Request::routeIs('college_essays') ? 'active' : '' }}">
                <a href="{{ route('college_essays') }}" class="menu-link">
                  <div>College Essays</div>
                </a>
              </li>
              <li class="menu-item {{ Request::routeIs('executive_function') ? 'active' : '' }}">
                <a href="{{ route('executive_function') }}" class="menu-link">
                  <div>Executive Function</div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page" style="margin-left: 260px !important; width: calc(100% - 260px) !important; position: relative !important; display: block !important;">
        <!-- Header with Profile -->
        <div class="d-flex justify-content-end align-items-center px-4 pt-1 pb-0" style="background: transparent; box-shadow: none; margin: 0;">
          <div class="d-flex align-items-center">
            <div class="dropdown">
              <a class="dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" style="text-decoration: none;">
                <div class="avatar" style="position: relative; width: 40px; height: 40px; background-color: #696cff; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                  <i class="bx bx-user-circle" style="color: white; font-size: 24px;"></i>
                  <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: #71DD37; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"></span>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar" style="position: relative; width: 40px; height: 40px; background-color: #696cff; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                          <i class="bx bx-user-circle" style="color: white; font-size: 24px;"></i>
                          <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background-color: #71DD37; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);"></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <span class="fw-semibold d-block">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                        <small class="text-muted">Admin</small>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                    <i class="bx bx-user me-2 text-primary"></i>
                    <span class="align-middle">My Profile</span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="#">
                    <i class="bx bx-cog me-2 text-primary"></i>
                    <span class="align-middle">Settings</span>
                  </a>
                </li>
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <li>
                  <form action="{{ route('logout') }}" method="POST" class="m-0" id="logoutForm">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center" id="logoutButton">
                      <i class="bx bx-power-off me-2 text-danger"></i>
                      <span class="align-middle">Log Out</span>
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            @yield('content')
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                © {{ date('Y') }} Zoffness College Prep, made with ❤️
              </div>
            </div>
          </footer>
          <!-- / Footer -->
        </div>
        <!-- / Content wrapper -->
      </div>
      <!-- / Layout container -->
    </div>
  </div>
  <!-- / Layout wrapper -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Logout Confirmation -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const logoutButton = document.getElementById('logoutButton');
      if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent the form from submitting immediately

          // SweetAlert Confirmation
          Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              // If user confirmed, submit the form
              document.getElementById('logoutForm').submit();
            }
          });
        });
      }
    });
  </script>

  <!-- Menu Toggle Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Force sidebar to be expanded by default
      document.body.classList.add('layout-menu-expanded');

      // Force all menu text to be visible
      const menuTexts = document.querySelectorAll('.menu-link div');
      menuTexts.forEach(text => {
        text.style.display = 'block';
        text.style.visibility = 'visible';
        text.style.opacity = '1';
      });

      // Force all menu icons to be visible
      const menuIcons = document.querySelectorAll('.menu-icon');
      menuIcons.forEach(icon => {
        icon.style.display = 'flex';
        icon.style.visibility = 'visible';
        icon.style.opacity = '1';
      });

      // Fix sidebar width
      const layoutMenu = document.querySelector('.layout-menu');
      if (layoutMenu) {
        layoutMenu.style.width = '260px';
        layoutMenu.style.position = 'fixed';
        layoutMenu.style.top = '0';
        layoutMenu.style.bottom = '0';
        layoutMenu.style.left = '0';
        layoutMenu.style.zIndex = '1000';
        layoutMenu.style.overflowY = 'auto';
        layoutMenu.style.backgroundColor = '#fff';
        layoutMenu.style.boxShadow = '0 0.125rem 0.375rem 0 rgba(161, 172, 184, 0.12)';
        layoutMenu.style.display = 'block';
        layoutMenu.style.visibility = 'visible';
        layoutMenu.style.transform = 'none';
        layoutMenu.style.opacity = '1';
      }

      // Fix layout page width
      const layoutPage = document.querySelector('.layout-page');
      if (layoutPage) {
        layoutPage.style.marginLeft = '260px';
        layoutPage.style.width = 'calc(100% - 260px)';
      }

      // Toggle menu on click (mobile)
      const menuToggle = document.querySelector('.layout-menu-toggle');
      if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
          e.preventDefault();
          document.body.classList.toggle('layout-menu-expanded');
        });
      }

      // Toggle submenu on click
      const menuToggles = document.querySelectorAll('.menu-toggle');
      menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          const menuItem = this.parentElement;
          menuItem.classList.toggle('open');
        });
      });

      // Add overlay for mobile menu
      if (!document.querySelector('.layout-overlay')) {
        const overlay = document.createElement('div');
        overlay.className = 'layout-overlay';
        document.body.appendChild(overlay);

        overlay.addEventListener('click', function() {
          document.body.classList.remove('layout-menu-expanded');
        });
      }

      // Dropdown functionality
      const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

      dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          const dropdownMenu = this.nextElementSibling;
          if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
          }
        });
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
          document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
          });
        }
      });

      // Set active menu item based on current URL
      const currentPath = window.location.pathname;
      const menuLinks = document.querySelectorAll('.menu-link');
      menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
          link.classList.add('active');

          // If in submenu, open parent
          const parentItem = link.closest('.menu-item');
          if (parentItem && parentItem.parentElement.classList.contains('menu-sub')) {
            const parentMenuitem = parentItem.parentElement.closest('.menu-item');
            if (parentMenuitem) {
              parentMenuitem.classList.add('open');
            }
          }
        }
      });

      // Apply navbar styling
      const navbar = document.querySelector('.layout-navbar');
      if (navbar) {
        navbar.style.position = 'relative';
        navbar.style.paddingTop = '0.25rem';
        navbar.style.paddingBottom = '0.2rem';
        navbar.style.height = 'var(--navbar-height)';
        navbar.style.flexWrap = 'nowrap';
        navbar.style.color = 'var(--body-color)';
        navbar.style.zIndex = '10';
        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        navbar.style.backdropFilter = 'saturate(200%) blur(6px)';
      }
    });
  </script>
</body>

</html>