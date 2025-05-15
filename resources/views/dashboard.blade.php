@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Basic Layout -->
<div class="row">
  <!-- Statistics Cards -->
  <div class="col-lg-8 mb-4 order-0">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-7">
          <div class="card-body">
            <h5 class="card-title text-primary">Welcome {{ Auth::user()->name }}! 🎉</h5>
            <!-- <h6 class="mb-4">Voted as the Best Tutoring Company in Westchester Country</h6> -->
            <p class="mb-4">You have access to the Zoffness College Prep admin dashboard. Manage users, tutors, students, and more from here.</p>
            <a href="{{ route('users') }}" class="btn btn-primary">Manage Users</a>
          </div>
        </div>
        <!-- <div class="col-sm-5 text-center text-sm-left">
          <div class="card-body pb-0 px-0 px-md-4">
            <img src="https://via.placeholder.com/200x140" height="140" alt="View Badge User">
          </div>
        </div> -->
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="card-info">
                <p class="card-text">Students</p>
                <div class="d-flex align-items-end mb-2">
    <h4 class="card-title mb-0 me-2">{{ $studentCount }}</h4>
    <!-- <small class="text-success">+8.2%</small> {{-- Optional: make this dynamic too --}} -->
</div>

              </div>
              <div class="card-icon">
                <span class="badge bg-label-primary rounded p-2">
                  <i class="bx bx-user-pin bx-sm"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="card-info">
                <p class="card-text">Tutors</p>
                <div class="d-flex align-items-end mb-2">
                <h4 class="card-title mb-0 me-2">{{ $tutorCount }}</h4>

                  <!-- <h4 class="card-title mb-0 me-2">12</h4> -->
                  <!-- <small class="text-success">+5.1%</small> -->
                </div>
              </div>
              <div class="card-icon">
                <span class="badge bg-label-primary rounded p-2">
                  <i class="bx bx-user-voice bx-sm"></i>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="row">
  <div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Sessions</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="recentSessions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentSessions">
            <a class="dropdown-item" href="{{ url('session') }}">View All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-time"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">SAT Prep Session</h6>
                <small class="text-muted">John Smith with Tutor Alex</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Today</small>
              </div>
            </div>
          </li>
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-time"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">College Essay Review</h6>
                <small class="text-muted">Sarah Johnson with Tutor Maria</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Yesterday</small>
              </div>
            </div>
          </li>
          <li class="d-flex">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-time"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">ACT Math Practice</h6>
                <small class="text-muted">Michael Brown with Tutor David</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">May 5</small>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Bookings</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="recentBookings" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentBookings">
            <a class="dropdown-item" href="{{ url('bookings') }}">View All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-calendar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">SAT Prep Package</h6>
                <small class="text-muted">Emma Wilson - 10 Sessions</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">New</small>
              </div>
            </div>
          </li>
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-calendar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">College Counseling</h6>
                <small class="text-muted">James Davis - 5 Sessions</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Confirmed</small>
              </div>
            </div>
          </li>
          <li class="d-flex">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-calendar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">ACT Complete Prep</h6>
                <small class="text-muted">Olivia Martinez - 15 Sessions</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Pending</small>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0">Recent Payments</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="recentPayments" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentPayments">
            <a class="dropdown-item" href="{{ url('payments') }}">View All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">$1,200.00</h6>
                <small class="text-muted">SAT Complete Package</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Completed</small>
              </div>
            </div>
          </li>
          <li class="d-flex mb-3">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">$850.00</h6>
                <small class="text-muted">College Essay Review</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Completed</small>
              </div>
            </div>
          </li>
          <li class="d-flex">
            <div class="avatar flex-shrink-0 me-3">
              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-dollar"></i></span>
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0">$350.00</h6>
                <small class="text-muted">Single Session Payment</small>
              </div>
              <div class="user-progress">
                <small class="fw-semibold">Pending</small>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
