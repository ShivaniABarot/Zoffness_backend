@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <!-- Compact Modern Dashboard -->
  <div class="compact-dashboard">
    <!-- Background Blur Effect -->
    <div class="dashboard-background">
      <div class="blur-overlay"></div>
      <div class="gradient-mesh"></div>
    </div>

    <!-- Compact Header -->
    <div class="compact-header">
      <div class="header-container">
        <div class="header-left">
          <h1 class="page-title">Dashboard</h1>
          <p class="page-subtitle">
                Good {{ date('H') < 12 ? 'morning' : (date('H') < 18 ? 'afternoon' : 'evening') }}, 
                {{ Auth::user() ? Auth::user()->username : 'Guest' }}
                <span class="date-badge">{{ date('M j, Y') }}</span>
            </p>
        </div>

        <div class="header-right">
          <!-- Profile Dropdown -->
          <div class="profile-dropdown">
            <div class="dropdown">
              <a class="dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" style="text-decoration: none;">
                <div class="profile-avatar">
                  <i class="bx bx-user-circle"></i>
                  <span class="status-indicator"></span>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item profile-header" href="#">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="profile-avatar-small">
                          <i class="bx bx-user-circle"></i>
                          <span class="status-indicator"></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <span class="fw-semibold d-block">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                        <small class="text-muted">{{ Auth::check() ? ucfirst(Auth::user()->role ?? 'User') : 'Guest' }}</small>
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
                  <form action="{{ route('logout') }}" method="POST" class="m-0" id="dashboardLogoutForm">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center" id="dashboardLogoutButton">
                      <i class="bx bx-power-off me-2 text-danger"></i>
                      <span class="align-middle">Log Out</span>
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">
      <!-- Stats Grid -->
      <div class="stats-grid">
        <!-- Students Card -->
        <div class="stat-card students" data-card="students" data-count="{{ $studentCount ?? 0 }}">
          <div class="stat-card-content">
            <div class="stat-icon">
              <i class="bx bx-user-pin"></i>
            </div>
            <div class="stat-info">
              <h3 class="stat-number" data-target="{{ $studentCount ?? 0 }}">{{ $studentCount ?? 0 }}</h3>
              <p class="stat-label">Students</p>
              <span class="stat-change positive">
                <i class="bx bx-trending-up"></i>
                +8.2%
              </span>
            </div>
          </div>
          <div class="stat-card-bg"></div>
        </div>

        <!-- Tutors Card -->
        <div class="stat-card tutors" data-card="tutors" data-count="{{ $tutorCount ?? 0 }}">
          <div class="stat-card-content">
            <div class="stat-icon">
              <i class="bx bx-user-voice"></i>
            </div>
            <div class="stat-info">
              <h3 class="stat-number" data-target="{{ $tutorCount ?? 0 }}">{{ $tutorCount ?? 0 }}</h3>
              <p class="stat-label">Tutors</p>
              <span class="stat-change neutral">
                <i class="bx bx-minus"></i>
                0%
              </span>
            </div>
          </div>
          <div class="stat-card-bg"></div>
        </div>

        <!-- Sessions Card -->
        <div class="stat-card sessions" data-card="sessions" data-count="156">
          <div class="stat-card-content">
            <div class="stat-icon">
              <i class="bx bx-calendar-event"></i>
            </div>
            <div class="stat-info">
              <h3 class="stat-number" data-target="156">156</h3>
              <p class="stat-label">Sessions</p>
              <span class="stat-change positive">
                <i class="bx bx-trending-up"></i>
                +15.3%
              </span>
            </div>
          </div>
          <div class="stat-card-bg"></div>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="content-grid">
        <!-- Enhanced Recent Activity Card -->
      <div class="activity-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="bx bx-history"></i>
            Recent Activity
          </h3>
          <div class="card-actions">
            <button class="card-action" id="activity-filters-btn" title="Filters">
              <i class="bx bx-filter-alt"></i>
            </button>
            <button class="card-action" id="activity-stats-btn" title="Statistics">
              <i class="bx bx-bar-chart-alt-2"></i>
            </button>
            <button class="card-action" id="activity-refresh-btn" title="Refresh">
              <i class="bx bx-refresh"></i>
            </button>
          </div>
        </div>

        <!-- Activity Filters Panel -->
        <div class="activity-filters" id="activity-filters" style="display: none;">
          <div class="filter-section">
            <h4 class="filter-title">Activity Type</h4>
            <div class="filter-buttons">
              <button class="filter-btn active" data-filter="all">All</button>
              <button class="filter-btn" data-filter="sessions">Sessions</button>
              <button class="filter-btn" data-filter="workshops">Workshops</button>
              <button class="filter-btn" data-filter="consultations">Consultations</button>
              <button class="filter-btn" data-filter="tests">Tests</button>
            </div>
          </div>
          <div class="filter-section">
            <h4 class="filter-title">Date Range</h4>
            <div class="filter-buttons">
              <button class="filter-btn active" data-date-filter="all">All Time</button>
              <button class="filter-btn" data-date-filter="today">Today</button>
              <button class="filter-btn" data-date-filter="week">This Week</button>
              <button class="filter-btn" data-date-filter="month">This Month</button>
            </div>
          </div>
          <div class="filter-section">
            <h4 class="filter-title">Status</h4>
            <div class="filter-buttons">
              <button class="filter-btn active" data-status-filter="all">All</button>
              <button class="filter-btn" data-status-filter="completed">Completed</button>
              <button class="filter-btn" data-status-filter="upcoming">Upcoming</button>
              <button class="filter-btn" data-status-filter="cancelled">Cancelled</button>
            </div>
          </div>
        </div>

        <!-- Activity Statistics Panel -->
        <div class="activity-stats" id="activity-stats" style="display: none;">
          <div class="stats-grid-mini">
            <div class="stat-mini">
              <div class="stat-mini-icon">
                <i class="bx bx-calendar-week"></i>
              </div>
              <div class="stat-mini-info">
                <span class="stat-mini-number">24</span>
                <span class="stat-mini-label">This Week</span>
              </div>
            </div>
            <div class="stat-mini">
              <div class="stat-mini-icon">
                <i class="bx bx-calendar"></i>
              </div>
              <div class="stat-mini-info">
                <span class="stat-mini-number">89</span>
                <span class="stat-mini-label">This Month</span>
              </div>
            </div>
            <div class="stat-mini">
              <div class="stat-mini-icon">
                <i class="bx bx-trending-up"></i>
              </div>
              <div class="stat-mini-info">
                <span class="stat-mini-number">94%</span>
                <span class="stat-mini-label">Success Rate</span>
              </div>
            </div>
            <div class="stat-mini">
              <div class="stat-mini-icon">
                <i class="bx bx-time"></i>
              </div>
              <div class="stat-mini-info">
                <span class="stat-mini-number">2.5h</span>
                <span class="stat-mini-label">Avg Duration</span>
              </div>
            </div>
          </div>
          <div class="popular-types">
            <h5>Most Popular Types</h5>
            <div class="type-list">
              <div class="type-item">
                <span class="type-name">SAT Prep</span>
                <span class="type-count">45%</span>
              </div>
              <div class="type-item">
                <span class="type-name">Math Tutoring</span>
                <span class="type-count">28%</span>
              </div>
              <div class="type-item">
                <span class="type-name">Essay Writing</span>
                <span class="type-count">18%</span>
              </div>
              <div class="type-item">
                <span class="type-name">Test Prep</span>
                <span class="type-count">9%</span>
              </div>
            </div>
          </div>
        </div>
          <div class="card-content">
            <div class="activity-list">
              @forelse($recentSessions ?? [] as $session)
              <div class="activity-item" data-type="{{ strtolower($session->session_type ?? 'session') }}" data-status="completed">
                <div class="activity-avatar {{ $session->session_type == 'regular' ? 'primary' : 'info' }}">
                  <i class="bx bx-book-reader"></i>
                </div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">{{ $session->title }}</h4>
                    <div class="activity-badges">
                      <span class="status-badge completed">
                        <i class="bx bx-check-circle"></i>
                        Completed
                      </span>
                      <span class="payment-badge paid">
                        <i class="bx bx-dollar-circle"></i>
                        Paid
                      </span>
                    </div>
                  </div>
                  <div class="activity-meta">
                    <span class="activity-type">{{ ucfirst($session->session_type) }}</span>
                    <span class="activity-price">${{ number_format($session->price_per_slot, 2) }}</span>
                    <span class="activity-duration">
                      <i class="bx bx-time"></i>
                      2h 30m
                    </span>
                    <span class="activity-location">
                      <i class="bx bx-video"></i>
                      Online
                    </span>
                  </div>
                  <div class="activity-participants">
                    <span class="participant-info">
                      <i class="bx bx-user"></i>
                      Student: John Smith
                    </span>
                    <span class="participant-info">
                      <i class="bx bx-user-voice"></i>
                      Tutor: Sarah Johnson
                    </span>
                  </div>
                  <div class="activity-footer">
                    <span class="activity-time">
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
                    </span>
                    <div class="activity-rating">
                      <div class="stars">
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bx-star"></i>
                      </div>
                      <span class="rating-text">4.0</span>
                    </div>
                  </div>
                </div>
                <div class="activity-actions">
                  <button class="action-btn view-btn" title="View Details">
                    <i class="bx bx-show"></i>
                  </button>
                  <button class="action-btn notes-btn" title="Add Notes">
                    <i class="bx bx-note"></i>
                  </button>
                  <button class="action-btn reschedule-btn" title="Reschedule">
                    <i class="bx bx-calendar-edit"></i>
                  </button>
                  <div class="action-dropdown">
                    <button class="action-btn dropdown-btn" title="More Actions">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-check"></i>
                        Mark Complete
                      </a>
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-copy"></i>
                        Duplicate
                      </a>
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-download"></i>
                        Export
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @empty
              <!-- Enhanced Mock Recent Activities -->
              <div class="activity-item" data-type="tests" data-status="completed">
                <div class="activity-avatar primary">
                  <i class="bx bx-book-reader"></i>
                </div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">SAT Diagnostic Assessment - Extended Time</h4>
                    <div class="activity-badges">
                      <span class="status-badge completed">
                        <i class="bx bx-check-circle"></i>
                        Completed
                      </span>
                      <span class="payment-badge paid">
                        <i class="bx bx-dollar-circle"></i>
                        Paid
                      </span>
                    </div>
                  </div>
                  <div class="activity-meta">
                    <span class="activity-type">Test</span>
                    <span class="activity-price">$200.00</span>
                    <span class="activity-duration">
                      <i class="bx bx-time"></i>
                      4h 30m
                    </span>
                    <span class="activity-location">
                      <i class="bx bx-building"></i>
                      Test Center
                    </span>
                  </div>
                  <div class="activity-participants">
                    <span class="participant-info">
                      <i class="bx bx-user"></i>
                      Student: Emily Davis
                    </span>
                    <span class="participant-info">
                      <i class="bx bx-user-check"></i>
                      Proctor: Michael Brown
                    </span>
                  </div>
                  <div class="activity-footer">
                    <span class="activity-time">2 hours ago</span>
                    <div class="activity-rating">
                      <div class="stars">
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                        <i class="bx bxs-star"></i>
                      </div>
                      <span class="rating-text">5.0</span>
                    </div>
                  </div>
                </div>
                <div class="activity-actions">
                  <button class="action-btn view-btn" title="View Results">
                    <i class="bx bx-show"></i>
                  </button>
                  <button class="action-btn notes-btn" title="Add Notes">
                    <i class="bx bx-note"></i>
                  </button>
                  <button class="action-btn reschedule-btn" title="Schedule Retake">
                    <i class="bx bx-calendar-plus"></i>
                  </button>
                  <div class="action-dropdown">
                    <button class="action-btn dropdown-btn" title="More Actions">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-download"></i>
                        Download Report
                      </a>
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-share"></i>
                        Share Results
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="activity-item" data-type="sessions" data-status="upcoming">
                <div class="activity-avatar info">
                  <i class="bx bx-edit-alt"></i>
                </div>
                <div class="activity-details">
                  <div class="activity-header">
                    <h4 class="activity-title">Math Tutoring Session - Algebra II</h4>
                    <div class="activity-badges">
                      <span class="status-badge upcoming">
                        <i class="bx bx-clock"></i>
                        Upcoming
                      </span>
                      <span class="payment-badge pending">
                        <i class="bx bx-time-five"></i>
                        Pending
                      </span>
                    </div>
                  </div>
                  <div class="activity-meta">
                    <span class="activity-type">Session</span>
                    <span class="activity-price">$180.00</span>
                    <span class="activity-duration">
                      <i class="bx bx-time"></i>
                      1h 30m
                    </span>
                    <span class="activity-location">
                      <i class="bx bx-video"></i>
                      Online
                    </span>
                  </div>
                  <div class="activity-participants">
                    <span class="participant-info">
                      <i class="bx bx-user"></i>
                      Student: Alex Johnson
                    </span>
                    <span class="participant-info">
                      <i class="bx bx-user-voice"></i>
                      Tutor: Dr. Lisa Chen
                    </span>
                  </div>
                  <div class="activity-footer">
                    <span class="activity-time">Tomorrow 3:00 PM</span>
                    <div class="activity-rating">
                      <span class="rating-text">New Session</span>
                    </div>
                  </div>
                </div>
                <div class="activity-actions">
                  <button class="action-btn view-btn" title="View Details">
                    <i class="bx bx-show"></i>
                  </button>
                  <button class="action-btn notes-btn" title="Add Notes">
                    <i class="bx bx-note"></i>
                  </button>
                  <button class="action-btn reschedule-btn" title="Reschedule">
                    <i class="bx bx-calendar-edit"></i>
                  </button>
                  <div class="action-dropdown">
                    <button class="action-btn dropdown-btn" title="More Actions">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-video"></i>
                        Join Meeting
                      </a>
                      <a href="#" class="dropdown-item">
                        <i class="bx bx-x"></i>
                        Cancel Session
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-calculator"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Full-Length Proctored Practice SAT Test with Regular Time</h4>
                  <p class="activity-desc">Regular - $150.00</p>
                  <span class="activity-time">6 hours ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-pencil"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Full-Length Proctored Practice SAT Test with 50% Extended Time</h4>
                  <p class="activity-desc">Extended - $175.00</p>
                  <span class="activity-time">8 hours ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-brain"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Full-Length Proctored Practice ACT Test with Regular Time</h4>
                  <p class="activity-desc">Regular - $160.00</p>
                  <span class="activity-time">Yesterday</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-time-five"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">College Essay Writing Workshop Session</h4>
                  <p class="activity-desc">Workshop - $120.00</p>
                  <span class="activity-time">Yesterday</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-trophy"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">SAT Math Subject Test Preparation</h4>
                  <p class="activity-desc">Regular - $140.00</p>
                  <span class="activity-time">2 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-graduation"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">College Application Review Session</h4>
                  <p class="activity-desc">Consultation - $100.00</p>
                  <span class="activity-time">2 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-file-blank"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">AP Literature Essay Writing Session</h4>
                  <p class="activity-desc">Advanced - $130.00</p>
                  <span class="activity-time">3 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-math"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">SAT Math Section Intensive Review</h4>
                  <p class="activity-desc">Regular - $110.00</p>
                  <span class="activity-time">3 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-chat"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">College Interview Preparation Workshop</h4>
                  <p class="activity-desc">Workshop - $95.00</p>
                  <span class="activity-time">4 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-library"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">ACT English Section Strategy Session</h4>
                  <p class="activity-desc">Regular - $125.00</p>
                  <span class="activity-time">4 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-line-chart"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">SAT Score Analysis and Improvement Plan</h4>
                  <p class="activity-desc">Consultation - $85.00</p>
                  <span class="activity-time">5 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-bookmark"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Personal Statement Writing Workshop</h4>
                  <p class="activity-desc">Workshop - $115.00</p>
                  <span class="activity-time">5 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-target-lock"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">ACT Science Section Practice Test</h4>
                  <p class="activity-desc">Regular - $105.00</p>
                  <span class="activity-time">6 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar info">
                  <i class="bx bx-bulb"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Study Skills and Time Management Session</h4>
                  <p class="activity-desc">Consultation - $90.00</p>
                  <span class="activity-time">6 days ago</span>
                </div>
              </div>

              <div class="activity-item">
                <div class="activity-avatar primary">
                  <i class="bx bx-medal"></i>
                </div>
                <div class="activity-details">
                  <h4 class="activity-title">Scholarship Application Review</h4>
                  <p class="activity-desc">Consultation - $120.00</p>
                  <span class="activity-time">1 week ago</span>
                </div>
              </div>
              @endforelse
            </div>
          </div>
        </div>

      <!-- Enhanced Modern Calendar Card -->
      <div class="calendar-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="bx bx-calendar-event"></i>
            Schedule Calendar
          </h3>
          <div class="card-actions">
            <button class="card-action" id="calendar-views-btn" title="Calendar Views">
              <i class="bx bx-grid-alt"></i>
            </button>
            <button class="card-action" id="calendar-sync-btn" title="Sync Calendars">
              <i class="bx bx-sync"></i>
            </button>
            <button class="card-action" id="calendar-settings-btn" title="Settings">
              <i class="bx bx-cog"></i>
            </button>
            <button class="card-action primary" id="new-event-btn" title="New Event">
              <i class="bx bx-plus"></i>
            </button>
          </div>
        </div>

        <!-- Calendar View Options -->
        <div class="calendar-views" id="calendar-views" style="display: none;">
          <div class="panel-header">
            <h5>Calendar Views</h5>
            <button class="close-panel-btn" data-target="calendar-views">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="view-buttons">
            <button class="view-btn active" data-view="dayGridMonth">
              <i class="bx bx-calendar"></i>
              Month
            </button>
            <button class="view-btn" data-view="timeGridWeek">
              <i class="bx bx-calendar-week"></i>
              Week
            </button>
            <button class="view-btn" data-view="timeGridDay">
              <i class="bx bx-calendar-alt"></i>
              Day
            </button>
            <button class="view-btn" data-view="listWeek">
              <i class="bx bx-list-ul"></i>
              Agenda
            </button>
          </div>
          <div class="calendar-filters">
            <h5>Event Categories</h5>
            <div class="category-filters">
              <label class="category-filter">
                <input type="checkbox" checked data-category="sessions">
                <span class="category-color sessions"></span>
                Sessions
              </label>
              <label class="category-filter">
                <input type="checkbox" checked data-category="workshops">
                <span class="category-color workshops"></span>
                Workshops
              </label>
              <label class="category-filter">
                <input type="checkbox" checked data-category="tests">
                <span class="category-color tests"></span>
                Tests
              </label>
              <label class="category-filter">
                <input type="checkbox" checked data-category="consultations">
                <span class="category-color consultations"></span>
                Consultations
              </label>
            </div>
          </div>
        </div>

        <!-- Calendar Sync Panel -->
        <div class="calendar-sync" id="calendar-sync" style="display: none;">
          <div class="panel-header">
            <h5>Calendar Sync</h5>
            <button class="close-panel-btn" data-target="calendar-sync">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="sync-options">
            <h5>External Calendar Integration</h5>
            <div class="sync-providers">
              <button class="sync-btn google">
                <i class="bx bxl-google"></i>
                <span>Google Calendar</span>
                <span class="sync-status connected">Connected</span>
              </button>
              <button class="sync-btn outlook">
                <i class="bx bxl-microsoft"></i>
                <span>Outlook</span>
                <span class="sync-status">Connect</span>
              </button>
              <button class="sync-btn apple">
                <i class="bx bxl-apple"></i>
                <span>Apple Calendar</span>
                <span class="sync-status">Connect</span>
              </button>
            </div>
          </div>
          <div class="sync-settings">
            <h5>Sync Settings</h5>
            <div class="setting-item">
              <label>
                <input type="checkbox" checked>
                Auto-sync new events
              </label>
            </div>
            <div class="setting-item">
              <label>
                <input type="checkbox" checked>
                Send email reminders
              </label>
            </div>
            <div class="setting-item">
              <label>
                <input type="checkbox">
                SMS notifications
              </label>
            </div>
          </div>
        </div>

        <!-- Calendar Settings Panel -->
        <div class="calendar-settings" id="calendar-settings" style="display: none;">
          <div class="panel-header">
            <h5>Calendar Settings</h5>
            <button class="close-panel-btn" data-target="calendar-settings">
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="settings-section">
            <h5>Time Zone</h5>
            <select class="timezone-select">
              <option value="America/New_York">Eastern Time (ET)</option>
              <option value="America/Chicago">Central Time (CT)</option>
              <option value="America/Denver">Mountain Time (MT)</option>
              <option value="America/Los_Angeles">Pacific Time (PT)</option>
            </select>
          </div>
          <div class="settings-section">
            <h5>Working Hours</h5>
            <div class="time-range">
              <input type="time" value="09:00" class="start-time">
              <span>to</span>
              <input type="time" value="17:00" class="end-time">
            </div>
          </div>
          <div class="settings-section">
            <h5>Availability Management</h5>
            <div class="availability-options">
              <button class="availability-btn">
                <i class="bx bx-time"></i>
                Set Availability
              </button>
              <button class="availability-btn">
                <i class="bx bx-block"></i>
                Block Time
              </button>
              <button class="availability-btn">
                <i class="bx bx-group"></i>
                Manage Resources
              </button>
            </div>
          </div>
        </div>

        <div class="card-content">
          <div class="calendar-container">
            <div class="calendar-toolbar">
              <div class="availability-indicator">
                <div class="indicator-item">
                  <span class="indicator-dot available"></span>
                  <span>Available</span>
                </div>
                <div class="indicator-item">
                  <span class="indicator-dot busy"></span>
                  <span>Busy</span>
                </div>
                <div class="indicator-item">
                  <span class="indicator-dot blocked"></span>
                  <span>Blocked</span>
                </div>
              </div>
              <div class="calendar-actions">
                <button class="action-btn secondary" id="today-btn">
                  <i class="bx bx-target-lock"></i>
                  Today
                </button>
                <button class="action-btn secondary" id="refresh-calendar">
                  <i class="bx bx-refresh"></i>
                </button>
              </div>
            </div>
            <div id="calendar"></div>
          </div>
        </div>

        <!-- Quick Event Creation Modal -->
        <div class="quick-event-modal" id="quick-event-modal" style="display: none;">
          <div class="modal-content">
            <div class="modal-header">
              <h4>Create New Event</h4>
              <button class="close-modal" id="close-event-modal">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <div class="modal-body">
              <form id="quick-event-form">
                <div class="form-group">
                  <label>Event Title</label>
                  <input type="text" name="title" placeholder="Enter event title" required>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" required>
                  </div>
                  <div class="form-group">
                    <label>Time</label>
                    <input type="time" name="time" required>
                  </div>
                </div>
                <div class="form-group">
                  <label>Duration</label>
                  <select name="duration">
                    <option value="30">30 minutes</option>
                    <option value="60" selected>1 hour</option>
                    <option value="90">1.5 hours</option>
                    <option value="120">2 hours</option>
                    <option value="180">3 hours</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Category</label>
                  <select name="category">
                    <option value="sessions">Session</option>
                    <option value="workshops">Workshop</option>
                    <option value="tests">Test</option>
                    <option value="consultations">Consultation</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Location</label>
                  <select name="location">
                    <option value="online">Online (Zoom)</option>
                    <option value="office">Office</option>
                    <option value="student-home">Student's Home</option>
                    <option value="library">Library</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Participants</label>
                  <input type="text" name="participants" placeholder="Add students, tutors...">
                </div>
                <div class="form-group">
                  <label>
                    <input type="checkbox" name="recurring">
                    Recurring Event
                  </label>
                </div>
                <div class="form-actions">
                  <button type="button" class="btn secondary" id="cancel-event">Cancel</button>
                  <button type="submit" class="btn primary">Create Event</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      </div> <!-- End Content Grid -->
    </div>
  </div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <style>
    /* Override container padding for dashboard */
    .container-xxl.container-p-y {
      padding-top: 0 !important;
      padding-bottom: 0 !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    /* Remove any additional spacing from content wrapper */
    .content-wrapper {
      padding-top: 0 !important;
    }

    /* Remove spacing from layout page */
    .layout-page {
      padding-top: 0 !important;
    }

    /* Hide or minimize the profile header section for dashboard */
    .layout-page > .d-flex.justify-content-end {
      display: none !important;
    }

    /* Clean Modern Dashboard Styles */
    .compact-dashboard {
      min-height: 100vh;
      position: relative;
      background: #f8fafc;
      padding: 0;
      margin: 0.5rem -1.5rem -1.5rem -1.5rem;
      overflow-x: hidden;
      border-radius: 1rem 1rem 0 0;
    }

    /* Simplified Background */
    .dashboard-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .blur-overlay,
    .gradient-mesh {
      display: none;
    }

    /* Clean Compact Header */
    .compact-header {
      position: relative;
      z-index: 10;
      padding: 1rem 1.5rem;
      margin: 0.75rem 0.75rem 0 0.75rem;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 0.75rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      transition: all 0.2s ease;
    }

    /* Blue line at top of header removed */

    /* Shimmer animation removed */

    .header-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 1400px;
      margin: 0 auto;
    }

    .header-left {
      flex: 1;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Profile Dropdown Styles */
    .profile-dropdown {
      position: relative;
    }

    .profile-avatar {
      width: 2.5rem;
      height: 2.5rem;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
    }

    .profile-avatar:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .profile-avatar i {
      color: white;
      font-size: 1.5rem;
    }

    .status-indicator {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 0.75rem;
      height: 0.75rem;
      background: #10b981;
      border-radius: 50%;
      border: 2px solid white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-small {
      width: 2.5rem;
      height: 2.5rem;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
      position: relative;
    }

    .profile-avatar-small i {
      color: white;
      font-size: 1.5rem;
    }

    /* Dropdown Menu Styling */
    .profile-dropdown .dropdown-menu {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 0.75rem;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      padding: 0.5rem;
      min-width: 220px;
      margin-top: 0.5rem;
    }

    .profile-dropdown .dropdown-item {
      border-radius: 0.5rem;
      padding: 0.75rem;
      margin-bottom: 0.25rem;
      transition: all 0.2s ease;
      border: none;
      background: transparent;
      width: 100%;
      text-align: left;
    }

    .profile-dropdown .dropdown-item:hover {
      background: rgba(59, 130, 246, 0.08);
      color: #1e293b;
      transform: translateX(2px);
    }

    .profile-dropdown .dropdown-item.profile-header {
      background: rgba(248, 250, 252, 0.8);
      border-radius: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .profile-dropdown .dropdown-item.profile-header:hover {
      background: rgba(241, 245, 249, 0.9);
      transform: none;
    }

    .profile-dropdown .dropdown-divider {
      margin: 0.5rem 0;
      border-color: rgba(226, 232, 240, 0.6);
    }

    .profile-dropdown .dropdown-item i {
      width: 1.25rem;
      text-align: center;
    }

    .page-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: #4a5568;
      margin: 0;
      line-height: 1.2;
    }

    .page-subtitle {
      font-size: 0.875rem;
      color: #6b7280;
      margin: 0.25rem 0 0 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .date-badge {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      padding: 0.125rem 0.5rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .header-stats {
      display: flex;
      gap: 1rem;
    }

    .mini-stat {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1.25rem;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 1rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.05);
      position: relative;
      overflow: hidden;
    }

    .mini-stat::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.02), rgba(14, 165, 233, 0.02));
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .mini-stat:hover {
      transform: translateY(-2px) scale(1.02);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
      border-color: rgba(37, 99, 235, 0.2);
    }

    .mini-stat:hover::before {
      opacity: 1;
    }

    .mini-stat-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.125rem;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .mini-stat-icon::before {
      content: '';
      position: absolute;
      top: -1px;
      left: -1px;
      right: -1px;
      bottom: -1px;
      border-radius: 0.875rem;
      background: linear-gradient(45deg, rgba(255, 255, 255, 0.2), transparent);
      z-index: -1;
    }

    .students-stat .mini-stat-icon {
      background: linear-gradient(135deg, #0ea5e9, #0284c7);
    }

    .tutors-stat .mini-stat-icon {
      background: linear-gradient(135deg, #1e40af, #1e3a8a);
    }

    .mini-stat:hover .mini-stat-icon {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .mini-stat-content {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      position: relative;
      z-index: 2;
    }

    .mini-stat-number {
      display: block;
      font-size: 1.5rem;
      font-weight: 800;
      color: #4a5568;
      line-height: 1;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      transition: all 0.3s ease;
    }

    .mini-stat:hover .mini-stat-number {
      transform: scale(1.05);
    }

    .mini-stat-label {
      font-size: 0.8rem;
      color: #6b7280;
      margin-top: 0.125rem;
      font-weight: 600;
      letter-spacing: 0.025em;
      transition: all 0.3s ease;
    }

    .mini-stat:hover .mini-stat-label {
      color: #4a5568;
    }

    /* Responsive Design for Header Stats */
    @media (max-width: 768px) {
      .header-container {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }

      .header-right {
        width: 100%;
        justify-content: center;
      }

      .header-stats {
        gap: 0.75rem;
        flex-wrap: wrap;
        justify-content: center;
      }

      .mini-stat {
        padding: 0.5rem 1rem;
        gap: 0.5rem;
      }

      .mini-stat-icon {
        width: 2rem;
        height: 2rem;
        font-size: 1rem;
      }

      .mini-stat-number {
        font-size: 1.25rem;
      }

      .mini-stat-label {
        font-size: 0.75rem;
      }
    }

    @media (max-width: 480px) {
      .header-stats {
        width: 100%;
        justify-content: space-around;
      }

      .mini-stat {
        flex: 1;
        min-width: 120px;
        justify-content: center;
      }
    }

    .header-actions {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.75rem;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.125rem;
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }

    .action-btn.secondary {
      background: rgba(107, 114, 128, 0.1);
      color: #6b7280;
    }

    .action-btn.secondary:hover {
      background: rgba(107, 114, 128, 0.2);
      color: #374151;
      transform: translateY(-1px);
    }

    .action-btn.primary {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      padding: 0 1rem;
      width: auto;
      gap: 0.5rem;
    }

    .action-btn.primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .action-btn span {
      font-size: 0.875rem;
      font-weight: 500;
    }

    /* Dashboard Content */
    .dashboard-content {
      position: relative;
      z-index: 5;
      padding: 0.75rem;
      max-width: 1400px;
      margin: 0 auto;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 0.75rem;
      margin-bottom: 1rem;
    }

    .stat-card {
      position: relative;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 0.75rem;
      padding: 1.25rem;
      overflow: hidden;
      transition: all 0.2s ease;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
      border-color: rgba(59, 130, 246, 0.3);
    }

    .stat-card-content {
      position: relative;
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-icon {
      width: 3rem;
      height: 3rem;
      border-radius: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      color: white;
      transition: all 0.2s ease;
    }

    .stat-card:hover .stat-icon {
      transform: scale(1.05);
    }

    .stat-card.students .stat-icon {
      background: #3b82f6;
    }

    .stat-card.tutors .stat-icon {
      background: #1d4ed8;
    }

    .stat-card.sessions .stat-icon {
      background: #4338ca;
    }

    .stat-info {
      text-align: right;
      flex: 1;
      margin-left: 1rem;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      margin: 0;
      line-height: 1;
      transition: all 0.2s ease;
    }

    .stat-card:hover .stat-number {
      transform: scale(1.02);
    }

    .stat-label {
      font-size: 0.875rem;
      color: #64748b;
      margin: 0.25rem 0 0.5rem 0;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .stat-card:hover .stat-label {
      color: #475569;
    }

    .stat-change {
      font-size: 0.8rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 0.375rem;
      padding: 0.25rem 0.75rem;
      border-radius: 1rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .stat-change.positive {
      color: #10b981;
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .stat-change.negative {
      color: #ef4444;
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .stat-change.neutral {
      color: #6b7280;
      background: rgba(107, 114, 128, 0.1);
      border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .stat-card:hover .stat-change {
      transform: translateY(-1px) scale(1.05);
    }

    .stat-change i {
      font-size: 0.875rem;
    }



    /* Content Grid */
    .content-grid {
      display: grid;
      grid-template-columns: 1fr 1.5fr;
      gap: 0.75rem;
      margin: 0;
    }

    /* Clean Activity & Calendar Cards */
    .activity-card, .calendar-card {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 0.75rem;
      overflow: hidden;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      position: relative;
      margin: 0.75rem 0;
    }

    /* Activity Filters Panel */
    .activity-filters {
      background: rgba(248, 250, 252, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 1rem;
      padding: 1.5rem;
      margin: 1rem 1.5rem;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.05);
    }

    .filter-section {
      margin-bottom: 1.5rem;
    }

    .filter-section:last-child {
      margin-bottom: 0;
    }

    .filter-title {
      font-size: 0.875rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.75rem 0;
    }

    .filter-buttons {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 0.5rem 1rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.75rem;
      background: rgba(255, 255, 255, 0.8);
      color: #6b7280;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-btn:hover {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      transform: translateY(-1px);
    }

    .filter-btn.active {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      border-color: #2563eb;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Activity Statistics Panel */
    .activity-stats {
      background: rgba(248, 250, 252, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 1rem;
      padding: 1.5rem;
      margin: 1rem 1.5rem;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.05);
    }

    .stats-grid-mini {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-mini {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 1rem;
      background: rgba(255, 255, 255, 0.8);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 0.75rem;
      transition: all 0.3s ease;
    }

    .stat-mini:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.12);
    }

    .stat-mini-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.5rem;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .stat-mini-info {
      display: flex;
      flex-direction: column;
    }

    .stat-mini-number {
      font-size: 1.25rem;
      font-weight: 700;
      color: #4a5568;
      line-height: 1;
    }

    .stat-mini-label {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 0.125rem;
    }

    .popular-types h5 {
      font-size: 0.875rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.75rem 0;
    }

    .type-list {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .type-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0.75rem;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .type-item:hover {
      background: rgba(37, 99, 235, 0.05);
    }

    .type-name {
      font-size: 0.875rem;
      color: #4a5568;
    }

    .type-count {
      font-size: 0.875rem;
      font-weight: 600;
      color: #2563eb;
    }

    .activity-card::before, .calendar-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #2563eb, #0ea5e9, #1e40af);
      opacity: 0;
      transition: opacity 0.3s ease;
      display: none; /* Remove the blue line hover effect */
    }

    .activity-card:hover, .calendar-card:hover {
      transform: translateY(-6px) scale(1.01);
      box-shadow: 0 15px 35px rgba(37, 99, 235, 0.15);
      border-color: rgba(37, 99, 235, 0.2);
    }

    /* Blue line hover effect removed */

    .card-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid rgba(226, 232, 240, 0.5);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(248, 250, 252, 0.5);
    }

    .card-title {
      font-size: 1rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-title i {
      font-size: 1rem;
      color: #3b82f6;
    }

    .card-action, .card-actions {
      display: flex;
      gap: 0.5rem;
    }

    .card-action {
      width: 2rem;
      height: 2rem;
      border-radius: 0.5rem;
      border: none;
      background: rgba(107, 114, 128, 0.1);
      color: #6b7280;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .card-action:hover {
      background: rgba(107, 114, 128, 0.2);
      color: #374151;
    }

    .card-content {
      padding: 1rem;
    }

    /* Activity List */
    .activity-list {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .activity-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 1rem;
      border-radius: 0.5rem;
      transition: all 0.2s ease;
      border: 1px solid rgba(226, 232, 240, 0.5);
      background: rgba(255, 255, 255, 0.8);
      margin-bottom: 0.5rem;
    }


    /* Animation for top statistics cards */
    .stat-card {
      transition: transform 0.25s ease-out, box-shadow 0.25s ease-out; /* Smooth transition for hover out */
    }

    .stat-card:hover {
      transform: translateY(-4px) scale(1.03); /* Subtle lift and scale effect */
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08), 0 3px 6px rgba(0, 0, 0, 0.05); /* Softer, layered shadow */
    }

    .activity-item:hover {
      background: rgba(59, 130, 246, 0.02);
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border-color: rgba(59, 130, 246, 0.2);
    }

    /* Enhanced Activity Details */
    .activity-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 0.75rem;
    }

    .activity-badges {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .status-badge, .payment-badge {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .status-badge.completed {
      background: rgba(34, 197, 94, 0.1);
      color: #16a34a;
    }

    .status-badge.upcoming {
      background: rgba(59, 130, 246, 0.1);
      color: #3b82f6;
    }

    .status-badge.cancelled {
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    .payment-badge.paid {
      background: rgba(34, 197, 94, 0.1);
      color: #16a34a;
    }

    .payment-badge.pending {
      background: rgba(245, 158, 11, 0.1);
      color: #f59e0b;
    }

    .activity-meta {
      display: flex;
      gap: 1rem;
      margin-bottom: 0.75rem;
      flex-wrap: wrap;
    }

    .activity-meta > span {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      font-size: 0.75rem;
      color: #6b7280;
    }

    .activity-type {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      padding: 0.125rem 0.5rem;
      border-radius: 0.375rem;
      font-weight: 500;
    }

    .activity-price {
      font-weight: 600;
      color: #16a34a;
    }

    .activity-participants {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
      margin-bottom: 0.75rem;
    }

    .participant-info {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      color: #4a5568;
    }

    .activity-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .activity-rating {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .stars {
      display: flex;
      gap: 0.125rem;
    }

    .stars i {
      font-size: 0.875rem;
      color: #fbbf24;
    }

    .rating-text {
      font-size: 0.75rem;
      font-weight: 600;
      color: #4a5568;
    }

    /* Activity Actions */
    .activity-actions {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-left: auto;
      flex-shrink: 0;
    }

    .action-btn {
      width: 2rem;
      height: 2rem;
      border-radius: 0.5rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      background: rgba(255, 255, 255, 0.8);
      color: #6b7280;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.875rem;
    }

    .action-btn:hover {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      transform: scale(1.1);
      border-color: #2563eb;
    }

    .action-dropdown {
      position: relative;
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 0.75rem;
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15);
      padding: 0.5rem;
      min-width: 150px;
      z-index: 1000;
      display: none;
    }

    .dropdown-menu.show {
      display: block;
    }

    .dropdown-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      color: #4a5568;
      text-decoration: none;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .dropdown-item:hover {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
    }

    .activity-avatar {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: white;
      flex-shrink: 0;
      transition: all 0.2s ease;
    }

    .activity-item:hover .activity-avatar {
      transform: scale(1.05);
    }

    .activity-avatar.primary {
      background: #3b82f6;
    }

    .activity-avatar.info {
      background: #0ea5e9;
    }

    .activity-details {
      flex: 1;
      min-width: 0;
    }

    .activity-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.25rem 0;
      line-height: 1.3;
      transition: color 0.3s ease;
    }

    .activity-item:hover .activity-title {
      color: #2563eb;
    }

    .activity-desc {
      font-size: 0.75rem;
      color: #6b7280;
      margin: 0 0 0.25rem 0;
      line-height: 1.3;
    }

    .activity-time {
      font-size: 0.75rem;
      color: #9ca3af;
    }

    .empty-state {
      text-align: center;
      padding: 2rem;
      color: #9ca3af;
    }

    .empty-state i {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      opacity: 0.5;
    }

    /* Clean Calendar Styles */
    .calendar-container {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 0.5rem;
      padding: 1rem;
      border: 1px solid rgba(226, 232, 240, 0.5);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    /* Calendar View Options */
    .calendar-views, .calendar-sync, .calendar-settings {
      background: rgba(248, 250, 252, 0.9);
      border: 1px solid rgba(226, 232, 240, 0.5);
      border-radius: 0.5rem;
      padding: 0;
      margin: 0.75rem 1rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    /* Panel Header */
    .panel-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 1rem;
      border-bottom: 1px solid rgba(226, 232, 240, 0.5);
      background: rgba(255, 255, 255, 0.5);
      border-radius: 0.5rem 0.5rem 0 0;
    }

    .panel-header h5 {
      font-size: 0.875rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0;
    }

    .close-panel-btn {
      width: 1.5rem;
      height: 1.5rem;
      border-radius: 0.25rem;
      border: none;
      background: rgba(107, 114, 128, 0.1);
      color: #6b7280;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
      font-size: 0.875rem;
    }

    .close-panel-btn:hover {
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
      transform: scale(1.1);
    }

    /* Panel Content */
    .calendar-views > .view-buttons,
    .calendar-views > .calendar-filters,
    .calendar-sync > .sync-options,
    .calendar-sync > .sync-settings,
    .calendar-settings > .settings-section {
      padding: 1rem;
    }

    .view-buttons {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .view-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.75rem;
      background: rgba(255, 255, 255, 0.8);
      color: #6b7280;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .view-btn:hover {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      transform: translateY(-1px);
    }

    .view-btn.active {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      border-color: #2563eb;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .calendar-filters h5 {
      font-size: 0.875rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.75rem 0;
    }

    .category-filters {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .category-filter {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.875rem;
      color: #4a5568;
    }

    .category-filter:hover {
      background: rgba(37, 99, 235, 0.05);
    }

    .category-color {
      width: 1rem;
      height: 1rem;
      border-radius: 0.25rem;
    }

    .category-color.sessions {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .category-color.workshops {
      background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .category-color.tests {
      background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .category-color.consultations {
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
    }

    /* Calendar Sync Panel */
    .sync-options h5, .sync-settings h5 {
      font-size: 0.875rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.75rem 0;
    }

    .sync-providers {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }

    .sync-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 1rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.75rem;
      background: rgba(255, 255, 255, 0.8);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .sync-btn:hover {
      background: rgba(37, 99, 235, 0.05);
      transform: translateY(-1px);
    }

    .sync-btn span:first-of-type {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4a5568;
    }

    .sync-status {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
      border-radius: 0.375rem;
      font-weight: 500;
    }

    .sync-status.connected {
      background: rgba(34, 197, 94, 0.1);
      color: #16a34a;
    }

    .setting-item {
      margin-bottom: 0.75rem;
    }

    .setting-item label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
      color: #4a5568;
      cursor: pointer;
    }

    /* Calendar Settings Panel */
    .settings-section {
      margin-bottom: 1.5rem;
    }

    .settings-section:last-child {
      margin-bottom: 0;
    }

    .settings-section h5 {
      font-size: 0.875rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0 0 0.75rem 0;
    }

    .timezone-select, .start-time, .end-time {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.5rem;
      background: rgba(255, 255, 255, 0.8);
      font-size: 0.875rem;
      color: #4a5568;
    }

    .time-range {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .time-range span {
      font-size: 0.875rem;
      color: #6b7280;
    }

    .availability-options {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .availability-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.75rem;
      background: rgba(255, 255, 255, 0.8);
      color: #6b7280;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .availability-btn:hover {
      background: rgba(37, 99, 235, 0.1);
      color: #2563eb;
      transform: translateY(-1px);
    }

    /* Calendar Toolbar */
    .calendar-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding: 1rem;
      background: rgba(248, 250, 252, 0.8);
      border-radius: 0.75rem;
      border: 1px solid rgba(37, 99, 235, 0.1);
    }

    .availability-indicator {
      display: flex;
      gap: 1rem;
    }

    .indicator-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      color: #6b7280;
    }

    .indicator-dot {
      width: 0.75rem;
      height: 0.75rem;
      border-radius: 50%;
    }

    .indicator-dot.available {
      background: #16a34a;
    }

    .indicator-dot.busy {
      background: #dc2626;
    }

    .indicator-dot.blocked {
      background: #6b7280;
    }

    .calendar-actions {
      display: flex;
      gap: 0.5rem;
    }

    /* Calendar Toolbar Styles */
    .calendar-toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding: 0.75rem;
      background: rgba(248, 250, 252, 0.8);
      border-radius: 0.75rem;
      border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .availability-indicator {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .indicator-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.875rem;
      color: #64748b;
    }

    .indicator-dot {
      width: 0.75rem;
      height: 0.75rem;
      border-radius: 50%;
    }

    .indicator-dot.available {
      background: #10b981;
    }

    .indicator-dot.busy {
      background: #ef4444;
    }

    .indicator-dot.blocked {
      background: #6b7280;
    }

    /* Calendar Action Buttons - Match UI Theme */
    .calendar-actions .action-btn {
      padding: 0.375rem 0.75rem;
      border: 1px solid rgba(226, 232, 240, 0.6);
      border-radius: 0.5rem;
      background: rgba(248, 250, 252, 0.8);
      color: #64748b;
      font-size: 0.8rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.25rem;
      box-shadow: none;
      min-height: 2rem;
    }

    .calendar-actions .action-btn:hover {
      background: rgba(241, 245, 249, 0.9);
      color: #475569;
      border-color: rgba(203, 213, 225, 0.8);
      transform: none;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .calendar-actions .action-btn.secondary {
      background: rgba(248, 250, 252, 0.8);
      border-color: rgba(226, 232, 240, 0.6);
      color: #64748b;
    }

    .calendar-actions .action-btn.secondary:hover {
      background: rgba(241, 245, 249, 0.9);
      color: #475569;
      border-color: rgba(203, 213, 225, 0.8);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    /* Today Button Specific Styling */
    #today-btn {
      font-weight: 500;
      letter-spacing: 0;
      min-width: 3.5rem;
      font-size: 0.8rem;
    }

    #today-btn i {
      font-size: 0.875rem;
    }

    /* Refresh Button */
    #refresh-calendar {
      min-width: 2rem;
      padding: 0.375rem;
    }

    #calendar {
      background: transparent;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    /* Enhanced Toolbar */
    .fc-toolbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(15px);
      padding: 1.25rem 1.5rem;
      border-radius: 1rem;
      margin-bottom: 1.5rem;
      border: 1px solid rgba(37, 99, 235, 0.1);
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.08);
      gap: 1rem;
    }

    .fc-toolbar-chunk {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    /* Left toolbar chunk - navigation buttons */
    .fc-toolbar-chunk:first-child {
      gap: 0.5rem;
    }

    .fc-toolbar-chunk:first-child .fc-button-group {
      margin-right: 1rem;
    }

    /* Navigation Button Group */
    .fc-button-group {
      display: flex !important;
      gap: 0.25rem !important;
      margin-right: 1.5rem !important;
    }

    /* Navigation Buttons */
    .fc-prev-button, .fc-next-button {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      color: white !important;
      border-radius: 0.75rem !important;
      padding: 0.75rem 1rem !important;
      font-weight: 600 !important;
      font-size: 0.875rem !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important;
      min-width: 44px !important;
      height: 44px !important;
      margin: 0 !important;
    }

    /* Today Button - Separate styling */
    .fc-today-button {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      color: white !important;
      border-radius: 0.75rem !important;
      padding: 0.75rem 1.25rem !important;
      font-weight: 600 !important;
      font-size: 0.875rem !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3) !important;
      min-width: 80px !important;
      height: 44px !important;
      margin-left: 1rem !important;
    }

    .fc-prev-button:hover, .fc-next-button:hover, .fc-today-button:hover {
      transform: translateY(-2px) scale(1.05) !important;
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4) !important;
      background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
    }

    .fc-prev-button:active, .fc-next-button:active, .fc-today-button:active {
      transform: translateY(0) scale(0.98) !important;
    }

    /* View Buttons */
    .fc-dayGridMonth-button, .fc-timeGridWeek-button, .fc-listWeek-button {
      background: rgba(37, 99, 235, 0.1) !important;
      border: 1px solid rgba(37, 99, 235, 0.2) !important;
      color: #2563eb !important;
      border-radius: 0.75rem !important;
      padding: 0.75rem 1.25rem !important;
      font-weight: 600 !important;
      font-size: 0.875rem !important;
      transition: all 0.3s ease !important;
      margin: 0 0.25rem !important;
    }

    .fc-dayGridMonth-button:hover, .fc-timeGridWeek-button:hover, .fc-listWeek-button:hover {
      background: rgba(37, 99, 235, 0.15) !important;
      transform: translateY(-1px) !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
    }

    .fc-button-active {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      color: white !important;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3) !important;
    }

    /* Title Styling */
    .fc-toolbar-title {
      font-size: 1.5rem !important;
      font-weight: 700 !important;
      color: #4a5568 !important;
      margin: 0 1rem !important;
      background: linear-gradient(135deg, #4a5568, #2563eb);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .fc-button {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      color: white !important;
      border-radius: 0.5rem !important;
      padding: 0.5rem 1rem !important;
      font-weight: 500 !important;
      transition: all 0.2s ease !important;
    }

    .fc-button:hover {
      transform: translateY(-1px) !important;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4) !important;
    }

    .fc-event {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      border-radius: 0.5rem !important;
      padding: 0.25rem 0.5rem !important;
      font-weight: 500 !important;
      transition: all 0.2s ease !important;
    }

    .fc-event:hover {
      transform: scale(1.02) !important;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3) !important;
    }

    /* Enhanced Calendar Grid - Clean Design */
    .fc-scrollgrid {
      border: none !important;
      border-radius: 1rem !important;
      overflow: hidden !important;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.05) !important;
      background: rgba(255, 255, 255, 0.98) !important;
    }

    .fc-col-header {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(14, 165, 233, 0.08)) !important;
      border-bottom: 1px solid rgba(37, 99, 235, 0.1) !important;
    }

    .fc-col-header-cell {
      padding: 1.25rem 0.75rem !important;
      border-right: none !important;
    }

    /* Add subtle separators only between weeks */
    .fc-daygrid-day[data-date*="-07"],
    .fc-daygrid-day[data-date*="-14"],
    .fc-daygrid-day[data-date*="-21"],
    .fc-daygrid-day[data-date*="-28"] {
      border-bottom: 1px solid rgba(37, 99, 235, 0.05) !important;
    }

    .fc-col-header-cell-cushion {
      color: #2563eb !important;
      font-weight: 700 !important;
      font-size: 0.875rem !important;
      text-transform: uppercase !important;
      letter-spacing: 0.75px !important;
    }

    /* Enhanced Day Cells - Clean Background */
    .fc-daygrid-day {
      background: rgba(255, 255, 255, 0.98) !important;
      border: none !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
      position: relative !important;
      min-height: 130px !important;
    }

    /* Remove table borders */
    .fc-scrollgrid-sync-table,
    .fc-col-header-row,
    .fc-daygrid-body,
    .fc-scrollgrid-sync-inner {
      border: none !important;
    }

    /* Clean grid lines */
    .fc-theme-standard td,
    .fc-theme-standard th {
      border: none !important;
    }

    .fc-daygrid-day::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.02), rgba(14, 165, 233, 0.02));
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
    }

    .fc-daygrid-day:hover {
      background: rgba(37, 99, 235, 0.05) !important;
      transform: scale(1.01) !important;
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.12) !important;
      border-radius: 0.5rem !important;
      z-index: 10 !important;
    }

    .fc-daygrid-day:hover::before {
      opacity: 1;
    }

    /* Day Numbers */
    .fc-daygrid-day-number {
      color: #4a5568 !important;
      font-weight: 700 !important;
      padding: 0.875rem !important;
      font-size: 1.1rem !important;
      transition: all 0.3s ease !important;
      position: relative !important;
      z-index: 2 !important;
    }

    .fc-daygrid-day:hover .fc-daygrid-day-number {
      color: #2563eb !important;
      transform: scale(1.15) !important;
    }

    /* Today Highlighting */
    .fc-day-today {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(14, 165, 233, 0.12)) !important;
      border: 2px solid rgba(37, 99, 235, 0.4) !important;
      box-shadow: 0 4px 20px rgba(37, 99, 235, 0.15) !important;
    }

    .fc-day-today .fc-daygrid-day-number {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      color: white !important;
      border-radius: 0.875rem !important;
      width: 2.75rem !important;
      height: 2.75rem !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0.375rem !important;
      box-shadow: 0 6px 15px rgba(37, 99, 235, 0.4) !important;
      font-weight: 800 !important;
    }

    /* Event Styling */
    .fc-event {
      background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
      border: none !important;
      border-radius: 0.75rem !important;
      padding: 0.5rem 0.75rem !important;
      font-weight: 600 !important;
      font-size: 0.8rem !important;
      transition: all 0.3s ease !important;
      margin: 0.125rem !important;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2) !important;
    }

    .fc-event:hover {
      transform: scale(1.05) translateY(-1px) !important;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35) !important;
      background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
    }

    .fc-event-title {
      font-weight: 600 !important;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
    }

    /* More Events Link */
    .fc-more-link {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(14, 165, 233, 0.1)) !important;
      color: #2563eb !important;
      border: 1px solid rgba(37, 99, 235, 0.2) !important;
      border-radius: 0.5rem !important;
      padding: 0.25rem 0.5rem !important;
      font-size: 0.75rem !important;
      font-weight: 600 !important;
      text-decoration: none !important;
      transition: all 0.2s ease !important;
      margin: 0.125rem !important;
      display: inline-block !important;
    }

    .fc-more-link:hover {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(14, 165, 233, 0.15)) !important;
      color: #1d4ed8 !important;
      transform: scale(1.05) !important;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2) !important;
    }

    /* Popover for more events */
    .fc-popover {
      background: rgba(255, 255, 255, 0.98) !important;
      border: 1px solid rgba(37, 99, 235, 0.2) !important;
      border-radius: 0.75rem !important;
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.15) !important;
      backdrop-filter: blur(10px) !important;
    }

    .fc-popover-header {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(14, 165, 233, 0.08)) !important;
      border-bottom: 1px solid rgba(37, 99, 235, 0.1) !important;
      border-radius: 0.75rem 0.75rem 0 0 !important;
      padding: 0.75rem 1rem !important;
      font-weight: 700 !important;
      color: #2563eb !important;
    }

    /* Quick Event Creation Modal */
    .quick-event-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .modal-content {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(37, 99, 235, 0.1);
      border-radius: 1.5rem;
      box-shadow: 0 20px 50px rgba(37, 99, 235, 0.2);
      max-width: 500px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem 1.5rem 1rem 1.5rem;
      border-bottom: 1px solid rgba(229, 231, 235, 0.3);
    }

    .modal-header h4 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #4a5568;
      margin: 0;
    }

    .close-modal {
      width: 2rem;
      height: 2rem;
      border-radius: 0.5rem;
      border: none;
      background: rgba(107, 114, 128, 0.1);
      color: #6b7280;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .close-modal:hover {
      background: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    .modal-body {
      padding: 1.5rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-size: 0.875rem;
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid rgba(37, 99, 235, 0.2);
      border-radius: 0.5rem;
      background: rgba(255, 255, 255, 0.8);
      font-size: 0.875rem;
      color: #4a5568;
      transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: flex-end;
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid rgba(229, 231, 235, 0.3);
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: 0.75rem;
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
    }

    .btn.secondary {
      background: rgba(107, 114, 128, 0.1);
      color: #6b7280;
    }

    .btn.secondary:hover {
      background: rgba(107, 114, 128, 0.2);
      color: #374151;
    }

    .btn.primary {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: white;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn.primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
      .content-grid {
        grid-template-columns: 1fr;
      }

      .header-container {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }

      .header-right {
        width: 100%;
        justify-content: space-between;
      }

      .activity-card, .calendar-card {
        margin: 1rem 0.5rem;
      }

      .activity-filters, .activity-stats, .calendar-views, .calendar-sync, .calendar-settings {
        margin: 1rem 0.5rem;
      }
    }

    @media (max-width: 768px) {
      .compact-header {
        padding: 0.75rem 1rem;
        margin: 0.5rem 0.5rem 0 0.5rem;
      }

      .dashboard-content {
        padding: 0.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
      }

      .stat-card {
        padding: 1rem;
      }

      .page-title {
        font-size: 1.5rem;
      }

      .activity-card, .calendar-card {
        margin: 0.5rem 0;
      }

      .card-header {
        padding: 0.75rem 1rem;
      }

      .card-content {
        padding: 0.75rem;
      }

      .fc-toolbar {
        flex-direction: column;
        gap: 0.5rem;
      }
    }

    @media (max-width: 640px) {
      .header-container {
        gap: 0.75rem;
      }

      .header-right {
        flex-direction: column;
        gap: 1rem;
        width: 100%;
      }

      .header-actions {
        justify-content: center;
      }

      .action-btn.primary span {
        display: none;
      }
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Calendar initialization
      var calendarEl = document.getElementById('calendar');
      if (calendarEl) {
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
          dayMaxEvents: 1,
          dayMaxEventRows: 1,
          moreLinkClick: 'popover',
          eventMaxStack: 1,
          @if(Route::has('calendar.events'))
          events: "{{ route('calendar.events') }}",
          @endif
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
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                  popup: 'swal2-modern'
                }
              });
              return;
            }

            @if(Route::has('calendar.bookings'))
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
                  html += '<h5 class="mb-3" style="color: #2563eb;">' + info.event.extendedProps.type + ' Bookings</h5>';
                  html += '<ul class="list-unstyled">';
                  data.forEach(booking => {
                    html += `
                      <li class="mb-3 p-3 rounded" style="background: rgba(37, 99, 235, 0.1); border-left: 4px solid #2563eb;">
                        <strong>Booking:</strong> ${booking.name}<br>
                        <strong>Student:</strong> ${booking.student_name}<br>
                        <strong>Sessions:</strong> ${booking.sessions}<br>
                        <strong>Status:</strong> <span class="badge" style="background: ${
                          booking.status === 'New' ? '#3b82f6' :
                          booking.status === 'Confirmed' ? '#10b981' :
                          booking.status === 'Pending' ? '#f59e0b' : '#2563eb'
                        }; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">${booking.status}</span>
                      </li>
                    `;
                  });
                  html += '</ul>';
                } else {
                  html += '<p style="color: #6b7280;">No bookings found for this type and date.</p>';
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
                  background: 'rgba(255, 255, 255, 0.95)',
                  backdrop: 'rgba(0, 0, 0, 0.4)',
                  customClass: {
                    popup: 'swal2-modern'
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
                  background: 'rgba(255, 255, 255, 0.95)',
                  backdrop: 'rgba(0, 0, 0, 0.4)',
                  customClass: {
                    popup: 'swal2-modern'
                  }
                });
              });
            @endif
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
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                  popup: 'swal2-modern'
                }
              });
            }
          }
        });

        calendar.render();
      }

      // Enhanced header stats animations
      document.querySelectorAll('.mini-stat').forEach(stat => {
        const numberElement = stat.querySelector('.mini-stat-number');
        const target = parseInt(numberElement.textContent) || 0;

        // Animate counter on page load
        setTimeout(() => {
          animateCounter(numberElement, 0, target, 1500);
        }, 300);

        // Enhanced hover effects
        stat.addEventListener('mouseenter', function() {
          const icon = this.querySelector('.mini-stat-icon');
          icon.style.transform = 'scale(1.1) rotate(5deg)';
        });

        stat.addEventListener('mouseleave', function() {
          const icon = this.querySelector('.mini-stat-icon');
          icon.style.transform = 'scale(1) rotate(0deg)';
        });

        // Click effect
        stat.addEventListener('click', function() {
          this.style.transform = 'translateY(0px) scale(0.98)';
          setTimeout(() => {
            this.style.transform = 'translateY(-2px) scale(1.02)';
          }, 150);

          // Re-animate counter
          animateCounter(numberElement, 0, target, 800);
        });
      });

      // Enhanced interactive animations for stat cards
      document.querySelectorAll('.stat-card').forEach(card => {
        // Counter animation
        const numberElement = card.querySelector('.stat-number');
        const target = parseInt(numberElement.getAttribute('data-target')) || 0;

        // Animate counter on page load
        setTimeout(() => {
          animateCounter(numberElement, 0, target, 2000);
        }, 500);

        // Enhanced hover effects
        card.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-12px) scale(1.03)';

          // Add subtle shake to icon
          const icon = this.querySelector('.stat-icon');
          icon.style.animation = 'none';
          setTimeout(() => {
            icon.style.animation = 'subtle-pulse 3s ease-in-out infinite, icon-shake 0.5s ease-in-out';
          }, 10);
        });

        card.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0) scale(1)';

          // Reset icon animation
          const icon = this.querySelector('.stat-icon');
          icon.style.animation = 'subtle-pulse 3s ease-in-out infinite';
        });

        // Click effect
        card.addEventListener('click', function() {
          this.style.transform = 'translateY(-8px) scale(0.98)';
          setTimeout(() => {
            this.style.transform = 'translateY(-12px) scale(1.03)';
          }, 150);

          // Re-animate counter
          animateCounter(numberElement, 0, target, 1000);
        });
      });

      // Counter animation function
      function animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        const range = end - start;

        function updateCounter(currentTime) {
          const elapsed = currentTime - startTime;
          const progress = Math.min(elapsed / duration, 1);

          // Easing function for smooth animation
          const easeOutQuart = 1 - Math.pow(1 - progress, 4);
          const current = Math.floor(start + (range * easeOutQuart));

          element.textContent = current;

          if (progress < 1) {
            requestAnimationFrame(updateCounter);
          } else {
            element.textContent = end;
          }
        }

        requestAnimationFrame(updateCounter);
      }

      // Smooth scroll animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      // Enhanced scroll animations
      document.querySelectorAll('.stat-card').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px) scale(0.95)';
        el.style.transition = `opacity 0.8s ease ${index * 0.1}s, transform 0.8s ease ${index * 0.1}s`;
        observer.observe(el);
      });

      document.querySelectorAll('.activity-card, .calendar-card').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(40px)';
        el.style.transition = `opacity 0.8s ease ${0.4 + index * 0.2}s, transform 0.8s ease ${0.4 + index * 0.2}s`;
        observer.observe(el);
      });

      // Stagger activity items animation
      document.querySelectorAll('.activity-item').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateX(-20px)';
        el.style.transition = `opacity 0.6s ease ${index * 0.05}s, transform 0.6s ease ${index * 0.05}s`;

        setTimeout(() => {
          el.style.opacity = '1';
          el.style.transform = 'translateX(0)';
        }, 1000 + index * 50);
      });

      // Add ripple effect to buttons
      document.querySelectorAll('.action-btn, .card-action').forEach(button => {
        button.addEventListener('click', function(e) {
          const ripple = document.createElement('span');
          const rect = this.getBoundingClientRect();
          const size = Math.max(rect.width, rect.height);
          const x = e.clientX - rect.left - size / 2;
          const y = e.clientY - rect.top - size / 2;

          ripple.style.width = ripple.style.height = size + 'px';
          ripple.style.left = x + 'px';
          ripple.style.top = y + 'px';
          ripple.classList.add('ripple');

          this.appendChild(ripple);

          setTimeout(() => {
            ripple.remove();
          }, 600);
        });
      });

      // Add ripple CSS
      const style = document.createElement('style');
      style.textContent = `
        .action-btn, .card-action {
          position: relative;
          overflow: hidden;
        }

        .ripple {
          position: absolute;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.6);
          transform: scale(0);
          animation: ripple-animation 0.6s linear;
          pointer-events: none;
        }

        @keyframes ripple-animation {
          to {
            transform: scale(4);
            opacity: 0;
          }
        }

        /* Custom Scrollbar */
        .activity-list::-webkit-scrollbar {
          width: 6px;
        }

        .activity-list::-webkit-scrollbar-track {
          background: rgba(37, 99, 235, 0.05);
          border-radius: 3px;
        }

        .activity-list::-webkit-scrollbar-thumb {
          background: linear-gradient(135deg, #2563eb, #0ea5e9);
          border-radius: 3px;
        }

        .activity-list::-webkit-scrollbar-thumb:hover {
          background: linear-gradient(135deg, #1d4ed8, #0284c7);
        }

        /* Enhanced Loading States */
        .stat-card, .activity-card, .calendar-card {
          position: relative;
        }

        .stat-card::after, .activity-card::after, .calendar-card::after {
          content: '';
          position: absolute;
          top: 0;
          left: -100%;
          width: 100%;
          height: 100%;
          background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
          animation: loading-shimmer 2s infinite;
          pointer-events: none;
          opacity: 0;
        }

        @keyframes loading-shimmer {
          0% { left: -100%; }
          100% { left: 100%; }
        }

        /* Floating Elements */
        .dashboard-content::before {
          content: '';
          position: absolute;
          top: 10%;
          right: 10%;
          width: 100px;
          height: 100px;
          background: radial-gradient(circle, rgba(37, 99, 235, 0.1), transparent);
          border-radius: 50%;
          animation: float 6s ease-in-out infinite;
          pointer-events: none;
        }

        .dashboard-content::after {
          content: '';
          position: absolute;
          bottom: 20%;
          left: 5%;
          width: 60px;
          height: 60px;
          background: radial-gradient(circle, rgba(14, 165, 233, 0.1), transparent);
          border-radius: 50%;
          animation: float 8s ease-in-out infinite reverse;
          pointer-events: none;
        }

        @keyframes float {
          0%, 100% { transform: translateY(0px) rotate(0deg); }
          50% { transform: translateY(-20px) rotate(180deg); }
        }
      `;
      document.head.appendChild(style);

      // Enhanced Activity Filters Functionality
      const activityFiltersBtn = document.getElementById('activity-filters-btn');
      const activityFilters = document.getElementById('activity-filters');
      const activityStatsBtn = document.getElementById('activity-stats-btn');
      const activityStats = document.getElementById('activity-stats');

      if (activityFiltersBtn && activityFilters) {
        activityFiltersBtn.addEventListener('click', function() {
          const isVisible = activityFilters.style.display !== 'none';
          activityFilters.style.display = isVisible ? 'none' : 'block';
          if (activityStats) activityStats.style.display = 'none';
        });
      }

      if (activityStatsBtn && activityStats) {
        activityStatsBtn.addEventListener('click', function() {
          const isVisible = activityStats.style.display !== 'none';
          activityStats.style.display = isVisible ? 'none' : 'block';
          if (activityFilters) activityFilters.style.display = 'none';
        });
      }

      // Filter buttons functionality
      document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const filterType = this.dataset.filter || this.dataset.dateFilter || this.dataset.statusFilter;
          const filterGroup = this.closest('.filter-section');

          // Remove active class from siblings
          filterGroup.querySelectorAll('.filter-btn').forEach(sibling => {
            sibling.classList.remove('active');
          });

          // Add active class to clicked button
          this.classList.add('active');

          // Apply filter logic
          filterActivities(filterType);
        });
      });

      // Enhanced Calendar Views Functionality
      const calendarViewsBtn = document.getElementById('calendar-views-btn');
      const calendarViews = document.getElementById('calendar-views');
      const calendarSyncBtn = document.getElementById('calendar-sync-btn');
      const calendarSync = document.getElementById('calendar-sync');
      const calendarSettingsBtn = document.getElementById('calendar-settings-btn');
      const calendarSettings = document.getElementById('calendar-settings');

      if (calendarViewsBtn && calendarViews) {
        calendarViewsBtn.addEventListener('click', function() {
          toggleCalendarPanel('views');
        });
      }

      if (calendarSyncBtn && calendarSync) {
        calendarSyncBtn.addEventListener('click', function() {
          toggleCalendarPanel('sync');
        });
      }

      if (calendarSettingsBtn && calendarSettings) {
        calendarSettingsBtn.addEventListener('click', function() {
          toggleCalendarPanel('settings');
        });
      }

      // Calendar view buttons
      document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const view = this.dataset.view;

          // Remove active class from siblings
          document.querySelectorAll('.view-btn').forEach(sibling => {
            sibling.classList.remove('active');
          });

          // Add active class to clicked button
          this.classList.add('active');

          // Change calendar view if calendar exists
          if (typeof calendar !== 'undefined' && calendar && view) {
            calendar.changeView(view);
          }
        });
      });

      // Quick Event Modal Functionality
      const newEventBtn = document.getElementById('new-event-btn');
      const quickEventModal = document.getElementById('quick-event-modal');
      const closeEventModal = document.getElementById('close-event-modal');
      const cancelEventBtn = document.getElementById('cancel-event');

      if (newEventBtn && quickEventModal) {
        newEventBtn.addEventListener('click', function() {
          quickEventModal.style.display = 'flex';
        });
      }

      if (closeEventModal && quickEventModal) {
        closeEventModal.addEventListener('click', function() {
          quickEventModal.style.display = 'none';
        });
      }

      if (cancelEventBtn && quickEventModal) {
        cancelEventBtn.addEventListener('click', function() {
          quickEventModal.style.display = 'none';
        });
      }

      // Quick Event Form Submission
      const quickEventForm = document.getElementById('quick-event-form');
      if (quickEventForm) {
        quickEventForm.addEventListener('submit', function(e) {
          e.preventDefault();

          const formData = new FormData(this);
          const eventData = {
            title: formData.get('title'),
            date: formData.get('date'),
            time: formData.get('time'),
            duration: formData.get('duration'),
            category: formData.get('category'),
            location: formData.get('location'),
            participants: formData.get('participants'),
            recurring: formData.get('recurring')
          };

          // Add event to calendar if it exists
          if (typeof calendar !== 'undefined' && calendar) {
            const startDateTime = eventData.date + 'T' + eventData.time + ':00';
            const endDateTime = new Date(new Date(startDateTime).getTime() + (parseInt(eventData.duration) * 60000));

            const categoryColors = {
              sessions: '#2563eb',
              workshops: '#16a34a',
              tests: '#dc2626',
              consultations: '#7c3aed'
            };

            calendar.addEvent({
              title: eventData.title,
              start: startDateTime,
              end: endDateTime.toISOString(),
              backgroundColor: categoryColors[eventData.category] || '#2563eb',
              borderColor: categoryColors[eventData.category] || '#1d4ed8',
              extendedProps: {
                category: eventData.category,
                location: eventData.location,
                participants: eventData.participants
              }
            });
          }

          // Close modal and show success message
          quickEventModal.style.display = 'none';
          this.reset();

          Swal.fire({
            title: 'Event Created!',
            text: 'Your event has been successfully added to the calendar.',
            icon: 'success',
            confirmButtonColor: '#2563eb'
          });
        });
      }

      // Enhanced Activity Actions
      document.addEventListener('click', function(e) {
        if (e.target.closest('.action-btn')) {
          const btn = e.target.closest('.action-btn');
          const action = btn.classList.contains('view-btn') ? 'view' :
                        btn.classList.contains('notes-btn') ? 'notes' :
                        btn.classList.contains('reschedule-btn') ? 'reschedule' :
                        btn.classList.contains('dropdown-btn') ? 'dropdown' : null;

          if (action === 'dropdown') {
            const dropdown = btn.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown-menu')) {
              dropdown.classList.toggle('show');
            }
          } else if (action) {
            handleActivityAction(action, btn);
          }
        }

        // Close dropdowns when clicking outside
        if (!e.target.closest('.action-dropdown')) {
          document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            menu.classList.remove('show');
          });
        }
      });

      // Close panel buttons
      document.querySelectorAll('.close-panel-btn').forEach(button => {
        button.addEventListener('click', function() {
          const targetId = this.getAttribute('data-target');
          const panel = document.getElementById(targetId);
          if (panel) {
            panel.style.display = 'none';
          }
        });
      });

      // Helper Functions
      function toggleCalendarPanel(panelType) {
        const panels = {
          views: calendarViews,
          sync: calendarSync,
          settings: calendarSettings
        };

        // Hide all panels
        Object.values(panels).forEach(panel => {
          if (panel) panel.style.display = 'none';
        });

        // Show selected panel
        const targetPanel = panels[panelType];
        if (targetPanel) {
          const isVisible = targetPanel.style.display !== 'none';
          targetPanel.style.display = isVisible ? 'none' : 'block';
        }
      }

      function filterActivities(filterType) {
        const activityItems = document.querySelectorAll('.activity-item');

        activityItems.forEach(item => {
          const itemType = item.dataset.type;
          const itemStatus = item.dataset.status;

          let shouldShow = true;

          if (filterType !== 'all') {
            if (['sessions', 'workshops', 'consultations', 'tests'].includes(filterType)) {
              shouldShow = itemType === filterType;
            } else if (['completed', 'upcoming', 'cancelled'].includes(filterType)) {
              shouldShow = itemStatus === filterType;
            } else if (['today', 'week', 'month'].includes(filterType)) {
              // Date filtering logic would go here
              shouldShow = true; // Simplified for demo
            }
          }

          item.style.display = shouldShow ? 'flex' : 'none';
        });
      }

      function handleActivityAction(action, btn) {
        const activityItem = btn.closest('.activity-item');
        const activityTitle = activityItem.querySelector('.activity-title').textContent;

        switch (action) {
          case 'view':
            Swal.fire({
              title: 'Activity Details',
              html: `
                <div style="text-align: left; margin: 1rem 0;">
                  <p><strong>Activity:</strong> ${activityTitle}</p>
                  <p><strong>Status:</strong> ${activityItem.dataset.status || 'N/A'}</p>
                  <p><strong>Type:</strong> ${activityItem.dataset.type || 'N/A'}</p>
                </div>
              `,
              icon: 'info',
              confirmButtonColor: '#2563eb'
            });
            break;
          case 'notes':
            Swal.fire({
              title: 'Add Notes',
              input: 'textarea',
              inputPlaceholder: 'Enter your notes here...',
              showCancelButton: true,
              confirmButtonColor: '#2563eb',
              confirmButtonText: 'Save Notes'
            }).then((result) => {
              if (result.isConfirmed && result.value) {
                Swal.fire({
                  title: 'Notes Saved!',
                  text: 'Your notes have been saved successfully.',
                  icon: 'success',
                  confirmButtonColor: '#2563eb'
                });
              }
            });
            break;
          case 'reschedule':
            Swal.fire({
              title: 'Reschedule Activity',
              text: `Reschedule: ${activityTitle}`,
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#2563eb',
              confirmButtonText: 'Open Scheduler'
            }).then((result) => {
              if (result.isConfirmed) {
                // Open the quick event modal for rescheduling
                if (quickEventModal) {
                  quickEventModal.style.display = 'flex';
                }
              }
            });
            break;
        }
      }

      // Refresh functionality
      const activityRefreshBtn = document.getElementById('activity-refresh-btn');
      if (activityRefreshBtn) {
        activityRefreshBtn.addEventListener('click', function() {
          this.style.transform = 'rotate(360deg)';
          setTimeout(() => {
            this.style.transform = 'rotate(0deg)';
          }, 500);

          // Simulate refresh
          setTimeout(() => {
            Swal.fire({
              title: 'Refreshed!',
              text: 'Activity data has been updated.',
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            });
          }, 500);
        });
      }

      const refreshCalendarBtn = document.getElementById('refresh-calendar');
      if (refreshCalendarBtn) {
        refreshCalendarBtn.addEventListener('click', function() {
          if (typeof calendar !== 'undefined' && calendar) {
            calendar.refetchEvents();
          }

          Swal.fire({
            title: 'Calendar Refreshed!',
            text: 'Calendar data has been updated.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
          });
        });
      }

      const todayBtn = document.getElementById('today-btn');
      if (todayBtn) {
        todayBtn.addEventListener('click', function() {
          if (typeof calendar !== 'undefined' && calendar) {
            calendar.today();
          }
        });
      }

      // Dashboard Logout Functionality
      const dashboardLogoutButton = document.getElementById('dashboardLogoutButton');
      if (dashboardLogoutButton) {
        dashboardLogoutButton.addEventListener('click', function(e) {
          e.preventDefault();

          Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out of your account.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            background: 'rgba(255, 255, 255, 0.98)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            customClass: {
              popup: 'swal2-modern'
            }
          }).then((result) => {
            if (result.isConfirmed) {
              document.getElementById('dashboardLogoutForm').submit();
            }
          });
        });
      }
    });
  </script>
@endpush