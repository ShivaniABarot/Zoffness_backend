<!DOCTYPE html>
<html lang="en" class="light-style" dir="ltr" data-theme="theme-default">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>@yield('title', 'Dashboard')</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('zoffnesscollegeprep-logo.png') }}">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <!-- Icons -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-variables.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/navbar-utilities.css') }}" />
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="{{ asset('css/custom-datatables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/action-buttons.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @stack('styles')
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
          <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="/zoffnesscollegeprep-logo.png" alt="Zoffness College Prep Logo" class="app-brand-logo demo" style="height: 40px; margin-left: 10px;">
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Zoffness</span> -->
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
          <li class="menu-item {{ request()->is('session') || request()->is('sat_act_packages') || request()->is('package') ? 'active open' : '' }}">
            <a href="#" class="menu-link menu-toggle">
              <i class="menu-icon bx bx-package"></i>
              <div>Packages</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->is('session') ? 'active' : '' }}">
                <a href="{{ url('session') }}" class="menu-link">
                  <i class="menu-icon bx bx-time"></i>
                  <div>Sessions</div>
                </a>
              </li>
              <li class="menu-item {{ request()->is('sat_act_packages') ? 'active' : '' }}">
                <a href="{{ url('sat_act_packages') }}" class="menu-link">
                  <i class="menu-icon bx bx-calendar"></i>
                  <div>SAT-ACT Packages</div>
                </a>
              </li>
              <li class="menu-item {{ request()->is('package') ? 'active' : '' }}">
                <a href="{{ url('package') }}" class="menu-link">
                  <i class="menu-icon bx bx-package"></i>
                  <div>College Admissions Packages</div>
                </a>
              </li>
              <li class="menu-item {{ request()->is('executive_package') ? 'active' : '' }}">
                <a href="{{ url('executive_package') }}" class="menu-link">
                  <i class="menu-icon bx bx-briefcase-alt"></i>
                  <div>Executive Packages</div>
                </a>
              </li>
              <li class="menu-item {{ request()->is('collage_essays_packages') ? 'active' : '' }}">
                <a href="{{ url('collage_essays_packages') }}" class="menu-link">
                  <i class="menu-icon bx bx-book-content"></i>
                  <div>Essays Packages</div>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li class="menu-item {{ request()->is('payments') ? 'active' : '' }}">
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
          </li> -->
          <!-- <li class="menu-item {{ request()->is('email-settings') ? 'active' : '' }}">
            <a href="{{ url('email-settings') }}" class="menu-link">
              <i class="menu-icon bx bx-cog"></i>
              <div>Email Settings</div>
            </a>
          </li> -->
          <!-- Inquiry Dropdown -->
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon bx bx-help-circle"></i>
              <div>Bookings</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ Request::routeIs('sat_act_course') ? 'active' : '' }}">
                <a href="{{ route('sat_act_course') }}" class="menu-link">
                  <div>SAT/ACT Course</div>
                </a>
              </li>
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
              <li class="menu-item {{ Request::routeIs('collegeadmission.index') ? 'active' : '' }}">
                <a href="{{ route('collegeadmission.index') }}" class="menu-link">
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
          <!-- Footer removed as requested -->
        </div>
        <!-- / Content wrapper -->
      </div>
      <!-- / Layout container -->
    </div>
  </div>
  <!-- / Layout wrapper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
  <script src="{{ asset('js/custom-datatables.js') }}"></script>
  @stack('scripts')
  <!-- SweetAlert Logout Confirmation -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const logoutButton = document.getElementById('logoutButton');
      if (logoutButton) {
        logoutButton.addEventListener('click', function (event) {
          event.preventDefault();
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
              document.getElementById('logoutForm').submit();
            }
          });
        });
      }
    });
  </script>
  <!-- Menu Toggle Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.body.classList.add('layout-menu-expanded');
      const menuTexts = document.querySelectorAll('.menu-link div');
      menuTexts.forEach(text => {
        text.style.display = 'block';
        text.style.visibility = 'visible';
        text.style.opacity = '1';
      });
      const menuIcons = document.querySelectorAll('.menu-icon');
      menuIcons.forEach(icon => {
        icon.style.display = 'flex';
        icon.style.visibility = 'visible';
        icon.style.opacity = '1';
      });
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
      const layoutPage = document.querySelector('.layout-page');
      if (layoutPage) {
        layoutPage.style.marginLeft = '260px';
        layoutPage.style.width = 'calc(100% - 260px)';
      }
      const menuToggle = document.querySelector('.layout-menu-toggle');
      if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
          e.preventDefault();
          document.body.classList.toggle('layout-menu-expanded');
        });
      }
      const menuToggles = document.querySelectorAll('.menu-toggle');
      menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
          e.preventDefault();
          const menuItem = this.parentElement;
          menuItem.classList.toggle('open');
        });
      });
      const submenuItems = document.querySelectorAll('.menu-sub .menu-item .menu-link');
      submenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
          const parentMenuItem = this.closest('.menu-sub').parentElement;
          if (parentMenuItem) {
            localStorage.setItem('activeMenuParent', parentMenuItem.querySelector('.menu-link').textContent.trim());
            if (!parentMenuItem.classList.contains('open')) {
              parentMenuItem.classList.add('open');
            }
          }
        });
      });
      const activeMenuParent = localStorage.getItem('activeMenuParent');
      if (activeMenuParent) {
        const menuItems = document.querySelectorAll('.menu-item > .menu-link');
        menuItems.forEach(item => {
          if (item.textContent.trim() === activeMenuParent) {
            const parentItem = item.closest('.menu-item');
            if (parentItem) {
              parentItem.classList.add('open');
              parentItem.classList.add('active-parent');
            }
          }
        });
      }
      if (!document.querySelector('.layout-overlay')) {
        const overlay = document.createElement('div');
        overlay.className = 'layout-overlay';
        document.body.appendChild(overlay);
        overlay.addEventListener('click', function () {
          document.body.classList.remove('layout-menu-expanded');
        });
      }
      const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
      dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
          e.preventDefault();
          const dropdownMenu = this.nextElementSibling;
          if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
          }
        });
      });
      document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
          document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
          });
        }
      });
      const currentPath = window.location.pathname;
      const menuLinks = document.querySelectorAll('.menu-link');
      menuLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
          link.classList.add('active');
          const menuItem = link.closest('.menu-item');
          if (menuItem) {
            menuItem.classList.add('active');
          }
          if (menuItem && menuItem.parentElement.classList.contains('menu-sub')) {
            const parentMenuItem = menuItem.parentElement.closest('.menu-item');
            if (parentMenuItem) {
              parentMenuItem.classList.add('open');
              parentMenuItem.classList.add('active-parent');
            }
          }
        }
      });
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