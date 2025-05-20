@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <!-- Basic Layout -->
  <div class="row">
    <!-- Welcome Card -->
    <div class="col-lg-8 mb-4 order-0">
    <div class="card shadow-sm border-0 hover-card">
      <div class="d-flex align-items-end row">
      <div class="col-sm-7">
        <div class="card-body">
        <h5 class="card-title text-primary mb-3">Welcome, {{ Auth::user()->username }}! 🎉</h5>
        <p class="mb-4 text-muted">Manage users, tutors, students, and more from the Zoffness College Prep admin
          dashboard.</p>
        <a href="{{ route('users') }}" class="btn btn-primary btn-s rounded-pill">Manage Users</a>
        </div>
      </div>
      </div>
    </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-lg-4 col-md-4 order-1">
    <div class="row">
      <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card shadow-sm border-0 hover-card">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="card-info">
          <p class="card-text text-muted mb-2"><a href="/student">Students</a></p>
          <h4 class="card-title mb-0">{{ $studentCount }}</h4>
          </div>
          <div class="card-icon">
          <span class="badge bg-primary rounded-circle p-3">
            <i class="bx bx-user-pin bx-md"></i>
          </span>
          </div>
        </div>
        </div>
      </div>
      </div>
      <div class="col-lg-6 col-md-12 col-6 mb-4">
      <div class="card shadow-sm border-0 hover-card">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="card-info">
          <p class="card-text text-muted mb-2"><a href="/tutors">Tutors</a></p>
          <h4 class="card-title mb-0">{{ $tutorCount }}</h4>
          </div>
          <div class="card-icon">
          <span class="badge bg-primary rounded-circle p-3">
            <i class="bx bx-user-voice bx-md"></i>
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
    <!-- Recent Sessions -->
    <div class="col-md-6 col-lg-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header d-flex align-items-center justify-content-between bg-transparent border-bottom">
      <h5 class="card-title m-0">Recent Sessions</h5>
      <div class="dropdown">
        <button class="btn p-0" type="button" id="recentSessions" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentSessions">
        <a class="dropdown-item" href="{{ url('session') }}">View All</a>
        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
        </div>
      </div>
      </div>
      <div class="card-body">
      <ul class="p-0 m-0 list-unstyled">
        @forelse($recentSessions as $session)
      <li class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }} hover-item">
      <div class="avatar flex-shrink-0 me-3">
        <span
        class="avatar-initial rounded-circle bg-label-{{ $session->session_type == 'regular' ? 'primary' : 'info' }}">
        <i class="bx bx-time"></i>
        </span>
      </div>
      <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
        <div class="me-2">
        <h6 class="mb-1">{{ $session->title }}</h6>
        <small class="text-muted">
        {{ ucfirst($session->session_type) }} - ${{ number_format($session->price_per_slot, 2) }}
        </small>
        </div>
        <div class="user-progress">
        <small class="fw-semibold text-black">
        @if($session->created_at && $session->created_at instanceof \Carbon\Carbon)
        @if($session->created_at->isToday())
      Today
      @elseif($session->created_at->isYesterday())
      Yesterday
      @else
      {{ $session->created_at->format('M d') }}
      @endif
      @else
      N/A
      @endif
        </small>
        </div>
      </div>
      </li>
      @empty
      <li class="text-center py-4">
      <div class="text-muted">
        <i class="bx bx-calendar-x fs-3 mb-2 opacity-50"></i>
        <p>No recent sessions found</p>
      </div>
      </li>
      @endforelse
      </ul>
      </div>
    </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-md-6 col-lg-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header d-flex align-items-center justify-content-between bg-transparent border-bottom">
      <h5 class="card-title m-0">Recent Bookings</h5>
      <div class="dropdown">
        <button class="btn p-0" type="button" id="recentBookings" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentBookings">
        <a class="dropdown-item" href="{{ url('bookings') }}">View All</a>
        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
        </div>
      </div>
      </div>
      <div class="card-body">
      <ul class="p-0 m-0 list-unstyled">
        @forelse($recentBookings as $booking)
        <li class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }} hover-item">
        <div class="avatar flex-shrink-0 me-3">
        <span class="avatar-initial rounded-circle bg-label-primary">
        <i class="bx {{ $booking['icon'] ?? 'bx-calendar' }}"></i>
        </span>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
        <div class="me-2">
        <h6 class="mb-1">{{ $booking['type'] }}</h6>
        <small class="text-muted">{{ $booking['student_name'] }} - {{ $booking['sessions'] }}</small>
        </div>
        <div class="user-progress">
        <small class="fw-semibold text-black">
          <span class="badge bg-label-{{
      $booking['status'] == 'New' ? 'info' :
      ($booking['status'] == 'Confirmed' ? 'success' :
      ($booking['status'] == 'Pending' ? 'warning' : 'primary'))
          }} rounded-pill" style="color: black;">
          {{ $booking['status'] }}
          </span>
        </small>
        </div>
        </div>
        </li>
      @empty
      <li class="text-center py-4">
      <div class="text-muted">
        <i class="bx bx-calendar-x fs-3 mb-2 opacity-50"></i>
        <p>No recent bookings found</p>
      </div>
      </li>
      @endforelse
      </ul>
      </div>
    </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-md-6 col-lg-4 mb-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header d-flex align-items-center justify-content-between bg-transparent border-bottom">
      <h5 class="card-title m-0">Recent Payments</h5>
      <div class="dropdown">
        <button class="btn p-0" type="button" id="recentPayments" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentPayments">
        <a class="dropdown-item" href="{{ url('payments') }}">View All</a>
        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
        </div>
      </div>
      </div>
      <div class="card-body">
      <ul class="p-0 m-0 list-unstyled">
        @forelse($recentPayments as $payment)
      <li class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }} hover-item">
      <div class="avatar flex-shrink-0 me-3">
        <span
        class="avatar-initial rounded-circle bg-label-{{ $payment->status == 'Completed' ? 'success' : 'warning' }}">
        <i class="bx bx-dollar"></i>
        </span>
      </div>
      <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
        <div class="me-2">
        <h6 class="mb-1">${{ number_format($payment->amount, 2) }}</h6>
        <small class="text-muted">
        @if($payment->package_id && $payment->package)
      {{ $payment->package->name }}
      @elseif($payment->session_id && $payment->session)
      {{ $payment->session->title }}
      @else
      {{ $payment->payment_method }} Payment
      @endif
        </small>
        </div>
        <div class="user-progress">
        <small class="fw-semibold text-black">
        <span
        class="badge bg-label-{{ $payment->status == 'Completed' ? 'success' : 'warning' }} rounded-pill"
        style="color: black;">
        {{ $payment->status }}
        </span>
        </small>
        </div>
      </div>
      </li>
      @empty
      <li class="text-center py-4">
      <div class="text-muted">
        <i class="bx bx-dollar-circle fs-3 mb-2 opacity-50"></i>
        <p>No recent payments found</p>
      </div>
      </li>
      @endforelse
      </ul>
      </div>
    </div>
    </div>
  </div>

  <!-- FullCalendar Section -->
  <div class="row">
    <div class="col-12 mb-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
      <h5 class="card-title m-0">Calendar</h5>
      </div>
      <div class="card-body">
      <div id="calendar"></div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <style>
    /* Card Styling */
    .card {
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
    padding: 1.25rem;
    background-color: transparent;
    }

    .card-body {
    overflow: visible;
    padding: 1.5rem;
    }

    .card-title {
    font-weight: 600;
    color: #333;
    }

    .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: background-color 0.3s;
    }

    .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    }

    /* List Item Hover Effect */
    .hover-item {
    transition: background-color 0.2s;
    padding: 0.5rem;
    border-radius: 0.25rem;
    }

    .hover-item:hover {
    background-color: #f8f9fa;
    }

    .avatar-initial {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    }

    .badge.rounded-circle {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    #calendar {
    max-height: none !important;
    overflow: visible !important;
    }

    .fc {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .fc-toolbar {
    background-color: #f8f9fa;
    padding: 0.75rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    }

    .fc-button {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #fff !important;
    text-transform: capitalize;
    border-radius: 0.25rem;
    }

    .fc-button:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
    }

    .fc-event {
    border-radius: 0.25rem;
    padding: 0.25rem;
    cursor: pointer;
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #fff !important;
    }

    .fc-event:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
    }

    .fc-daygrid-day-number {
    color: #333;
    }

    .fc-daygrid-day-top {
    padding: 0.5rem;
    }

    .fc-more-link {
    color: #007bff !important;
    font-weight: 500;
    text-decoration: none;
    }

    .fc-more-link:hover {
    color: #0056b3 !important;
    text-decoration: underline;
    }

    .event-math-tutoring {
    background-color: #6f42c1 !important;
    /* Purple */
    border-color: #6f42c1 !important;
    }

    .event-math-tutoring:hover {
    background-color: #5a32a3 !important;
    /* Darker purple */
    border-color: #5a32a3 !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
    .card-body {
      padding: 1rem;
    }

    .avatar-initial {
      width: 32px;
      height: 32px;
      font-size: 1rem;
    }

    .badge.rounded-circle {
      width: 40px;
      height: 40px;
    }

    .fc-toolbar {
      flex-direction: column;
      gap: 0.5rem;
    }
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        selectable: true,
        eventDisplay: 'block',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        dayMaxEvents: 3,
        dayMaxEventRows: true,
        events: "{{ route('calendar.events') }}",
        eventClassNames: function (arg) {
          const eventType = arg.event.extendedProps.type || '';
          switch (eventType.toLowerCase()) {
            case 'college essay':
              return 'event-college-essay';
            case 'sat prep':
              return 'event-sat-prep';
            case 'act prep':
              return 'event-act-prep';
            default:
              return 'event-default';
          }
        },
        eventClick: function (info) {
          const bookingIds = info.event.extendedProps.booking_ids || [];
          if (bookingIds.length === 0) {
            Swal.fire({
              title: 'No Bookings',
              text: 'No bookings found for this type and date.',
              icon: 'info',
              showCloseButton: true,
              confirmButtonText: 'OK',
              showCancelButton: true,
              cancelButtonText: 'Close',
              customClass: {
                popup: 'swal2-custom',
                title: 'swal2-title',
                confirmButton: 'btn btn-primary rounded-pill',
                cancelButton: 'btn btn-outline-secondary rounded-pill',
                closeButton: 'swal2-close-custom'
              }
            });
            return;
          }

          fetch('{{ route('calendar.bookings') }}?type=' + encodeURIComponent(info.event.extendedProps.type) +
            '&date=' + info.event.start.toDateString() +
            '&booking_ids=' + bookingIds.join(','), {
            headers: {
              'Accept': 'application/json'
            }
          })
            .then(response => response.json())
            .then(data => {
              let html = '<div class="text-start">';
              if (data.length > 0) {
                html += '<h5 class="mb-3 text-primary">' + info.event.extendedProps.type + ' Bookings</h5>';
                html += '<ul class="list-unstyled">';
                data.forEach(booking => {
                  html += `
                    <li class="mb-3 p-3 bg-light rounded">
                      <strong>Booking:</strong> ${booking.name}<br>
                      <strong>Student:</strong> ${booking.student_name}<br>
                      <strong>Sessions:</strong> ${booking.sessions}<br>
                      <strong>Status:</strong> <span class="badge bg-label-${
                        booking.status === 'New' ? 'info' :
                        booking.status === 'Confirmed' ? 'success' :
                        booking.status === 'Pending' ? 'warning' : 'primary'
                      } rounded-pill" style="color: black;">${booking.status}</span>
                    </li>
                  `;
                });
                html += '</ul>';
              } else {
                html += '<p class="text-muted">No bookings found for this type and date.</p>';
              }
              html += '</div>';

              Swal.fire({
                title: info.event.extendedProps.type + ' on ' + info.event.start.toLocaleDateString('en-US', {
                  weekday: 'long', month: 'long', day: 'numeric'
                }),
                html: html,
                icon: 'info',
                showCloseButton: true,
                confirmButtonText: 'OK',
                customClass: {
                  popup: 'swal2-custom',
                  title: 'swal2-title',
                  confirmButton: 'btn btn-primary rounded-pill'
                }
              });
            })
            .catch(error => {
              Swal.fire({
                title: 'Error',
                text: 'Failed to fetch booking details.',
                icon: 'error',
                showCloseButton: true,
                confirmButtonText: 'OK',
                customClass: {
                  popup: 'swal2-custom',
                  title: 'swal2-title',
                  confirmButton: 'btn btn-primary rounded-pill'
                }
              });
            });
        },
        selectAllow: function (selectInfo) {
          return selectInfo.start.getDay() === 6;
        },
        dateClick: function (info) {
          if (info.date.getDay() !== 6) {
            Swal.fire({
              title: 'Invalid Selection',
              text: 'Only Saturdays are allowed for selection.',
              icon: 'warning',
              showCloseButton: true,
              confirmButtonText: 'OK',
              customClass: {
                popup: 'swal2-custom',
                title: 'swal2-title',
                confirmButton: 'btn btn-primary rounded-pill'
              }
            });
          }
        }
      });

      calendar.render();
    });
  </script>
@endpush