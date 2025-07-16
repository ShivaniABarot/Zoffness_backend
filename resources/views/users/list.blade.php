@extends('layouts.app')

@section('content')
<div class="modern-users-section">
    <!-- Enhanced Header -->
    <div class="users-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="section-title">
                    <i class="bx bx-group"></i>
                    User Management
                </h1>
                <p class="section-subtitle">Manage and organize your users efficiently</p>
            </div>
            <div class="header-actions">
                <button class="action-btn secondary" id="refreshUsers" title="Refresh">
                    <i class="bx bx-refresh"></i>
                </button>
                <a href="{{ route('users.create') }}" class="action-btn primary">
                    <i class="bx bx-plus"></i>
                    <span>Add User</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Enhanced Controls -->
    <div class="users-controls">
        <div class="controls-left">
            <div class="search-container">
                <i class="bx bx-search search-icon"></i>
                <input type="text" id="userSearch" class="search-input" placeholder="Search users...">
            </div>
            <div class="filter-container">
                <select id="roleFilter" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="tutor">Tutor</option>
                    <option value="parent">Parent</option>
                </select>
            </div>
        </div>
        <div class="controls-right">
            <div class="export-buttons">
                <button class="export-btn excel" id="exportExcel">
                    <i class="bx bx-file"></i>
                    Excel
                </button>
                <button class="export-btn pdf" id="exportPDF">
                    <i class="bx bx-file-pdf"></i>
                    PDF
                </button>
            </div>
            <div class="view-toggle">
                <button class="view-btn active" data-view="cards" title="Card View">
                    <i class="bx bx-grid-alt"></i>
                </button>
                <button class="view-btn" data-view="table" title="Table View">
                    <i class="bx bx-list-ul"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Users Content -->
    <div class="users-content">
        <!-- Card View -->
        <div class="users-grid" id="cardView">
            @forelse ($users as $index => $user)
            <div class="user-card" 
     data-role="{{ $user->role }}" 
     data-search="{{ strtolower($user->first_name . ' ' . $user->last_name . ' ' . $user->email . ' ' . $user->role) }}">

                    <div class="card-header">
                        <div class="user-avatar">
                            <i class="bx bx-user"></i>
                            <span class="status-dot {{ $user->role }}"></span>
                        </div>
                        <div class="card-actions">
                            <div class="dropdown">
                                <button class="action-menu-btn" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
                                            <i class="bx bx-show me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                            <i class="bx bx-edit me-2"></i>Edit User
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" onclick="deleteUser({{ $user->id }})">
                                            <i class="bx bx-trash me-2"></i>Delete User
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    <h3 class="user-name">{{ $user->firstname }} {{ $user->lastname }}</h3>
                    <p class="user-email">{{ $user->email }}</p>
                        <div class="user-role">
                            <span class="role-badge {{ $user->role }}">
                                <i class="bx {{ $user->role == 'admin' ? 'bx-crown' : ($user->role == 'tutor' ? 'bx-chalkboard' : 'bx-user-circle') }}"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="quick-actions">
                            <a href="{{ route('users.show', $user->id) }}" class="quick-btn view" title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="quick-btn edit" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="quick-btn delete" onclick="deleteUser({{ $user->id }})" title="Delete">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bx bx-user-x"></i>
                    </div>
                    <h3>No Users Found</h3>
                    <p>Start by creating your first user</p>
                    <a href="{{ route('users.create') }}" class="action-btn primary">
                        <i class="bx bx-plus"></i>
                        Create User
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Modern Enhanced Table View -->
        <div class="ultra-modern-table-container" id="tableView" style="display: none;">
            <div class="glass-table-wrapper">
                <div class="modern-table-header">
                    <div class="header-left-section">
                        <div class="table-title-modern">
                            <div class="title-icon-wrapper">
                                <i class="bx bx-grid-alt"></i>
                            </div>
                            <div class="title-content">
                                <h3>Users Directory</h3>
                                <p>Manage your user accounts</p>
                            </div>
                        </div>
                    </div>
                    <div class="header-right-section">
                        <div class="stats-cards">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="bx bx-user"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-number">{{ count($users) }}</span>
                                    <span class="stat-label">Total Users</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-table-container">
                    <table id="usersTable" class="ultra-modern-table">
                        <thead class="glass-table-head">
                            <tr class="header-row">
                                <th class="modern-header-cell">
                                    <div class="header-cell-content">
                                        <span class="header-label">#</span>
                                    </div>
                                </th>
                                <th class="modern-header-cell">
                                    <div class="header-cell-content">
                                        <div class="header-icon-modern">
                                            <i class="bx bx-user"></i>
                                        </div>
                                        <span class="header-label">User</span>
                                    </div>
                                </th>
                                <th class="modern-header-cell">
                                    <div class="header-cell-content">
                                        <div class="header-icon-modern">
                                            <i class="bx bx-envelope"></i>
                                        </div>
                                        <span class="header-label">Email</span>
                                    </div>
                                </th>
                                <th class="modern-header-cell text-center">
                                    <div class="header-cell-content justify-content-center">
                                        <div class="header-icon-modern">
                                            <i class="bx bx-shield"></i>
                                        </div>
                                        <span class="header-label">Role</span>
                                    </div>
                                </th>
                                <th class="modern-header-cell text-center">
                                    <div class="header-cell-content justify-content-center">
                                        <div class="header-icon-modern">
                                            <i class="bx bx-cog"></i>
                                        </div>
                                        <span class="header-label">Actions</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="glass-table-body">
                            @forelse ($users as $index => $user)
                                <tr class="modern-table-row" data-user-id="{{ $user->id }}">
                                    <td class="modern-table-cell">
                                        <div class="modern-cell-content">
                                            <div class="row-number-modern">{{ $index + 1 }}</div>
                                        </div>
                                    </td>
                                    <td class="modern-table-cell">
                                        <div class="modern-cell-content">
                                            <div class="user-profile-modern">
                                                <div class="user-avatar-modern">
                                                    <div class="avatar-circle">
                                                        <i class="bx bx-user"></i>
                                                    </div>
                                                </div>
                                                <div class="user-info-modern">
                                                    <div class="user-name-modern">{{ $user->username }}</div>
                                                    <div class="user-id-modern">ID: {{ $user->id }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="modern-table-cell">
                                        <div class="modern-cell-content">
                                            <div class="email-modern">
                                                <div class="email-icon-modern">
                                                    <i class="bx bx-envelope"></i>
                                                </div>
                                                <span class="email-text-modern">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="modern-table-cell text-center">
                                        <div class="modern-cell-content justify-content-center">
                                            <div class="role-badge-modern role-{{ $user->role }}">
                                                <div class="badge-icon">
                                                    <i class="bx bx-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'tutor' ? 'chalkboard' : 'user') }}"></i>
                                                </div>
                                                <span class="badge-text">{{ ucfirst($user->role) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="modern-table-cell text-center">
                                        <div class="modern-cell-content justify-content-center">
                                            <div class="action-buttons-modern">
                                                <a href="{{ route('users.show', $user->id) }}" class="action-btn-glass view" title="View User">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user->id) }}" class="action-btn-glass edit" title="Edit User">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="action-btn-glass delete" onclick="deleteUser({{ $user->id }})" title="Delete User">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="5" class="empty-cell">
                                        <div class="empty-state-table">
                                            <div class="empty-icon">
                                                <i class="bx bx-user-x"></i>
                                            </div>
                                            <div class="empty-title">No Users Found</div>
                                            <div class="empty-subtitle">There are no users to display at the moment.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  function deleteUser(userID) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('users.delete', '') }}/' + userID,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message || 'User deleted successfully.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete user.',
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON?.message || 'An unexpected error occurred.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>

<!-- Modern Users Section Styles -->
<style>
/* Simple Hover Effects and Clean Animations */

/* Simple fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modern Users Section */
.modern-users-section {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    animation: fadeIn 0.6s ease-out;
}

/* Enhanced Header */
.users-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 1.2rem;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: slideUp 0.6s ease-out;
    position: relative;
    overflow: hidden;
}



.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 1;
}

.header-left {
    flex: 1;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}



.section-title i {
    color: #3b82f6;
    font-size: 2rem;
    filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.2));
}



.section-subtitle {
    color: #64748b;
    margin: 0.5rem 0 0 0;
    font-size: 0.95rem;
}



.header-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.action-btn {
    padding: 0.75rem 1.25rem;
    border-radius: 0.75rem;
    border: none;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}



.action-btn.primary {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
}



.action-btn.secondary {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    color: #64748b;
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
}



.action-btn i {
    transition: transform 0.3s ease;
}



/* Modern Alert */
.modern-alert {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 0.75rem;
    color: #166534;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}



/* Enhanced Controls */
.users-controls {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 1.2rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: slideUp 0.6s ease-out 0.2s both;
    position: relative;
    overflow: hidden;
}



.controls-left {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex: 1;
}

.controls-right {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.search-container {
    position: relative;
    flex: 1;
    max-width: 300px;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 1.125rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    background: #f8fafc;
    font-size: 0.875rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 0.75rem;
    background: rgba(248, 250, 252, 0.8);
    font-size: 0.875rem;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
}

.export-buttons {
    display: flex;
    gap: 0.5rem;
}

.export-btn {
    padding: 0.75rem 1rem;
    border: 1px solid;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
}

.export-btn.excel {
    color: #059669;
    border-color: rgba(5, 150, 105, 0.3);
}

.export-btn.pdf {
    color: #dc2626;
    border-color: rgba(220, 38, 38, 0.3);
}

.view-toggle {
    display: flex;
    background: rgba(248, 250, 252, 0.8);
    border-radius: 0.75rem;
    padding: 0.25rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.view-btn {
    padding: 0.5rem 0.75rem;
    border: none;
    background: transparent;
    border-radius: 0.5rem;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.view-btn.active {
    background: #3b82f6;
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}



/* Users Grid */
.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    padding: 0.5rem;
}

.user-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid #e2e8f0;
    border-radius: 1.2rem;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    animation: slideUp 0.6s ease-out both;
    position: relative;
}

.user-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05), transparent);
    transition: left 0.8s ease;
    z-index: 0;
}

.user-card:nth-child(1) { animation-delay: 0.1s; }
.user-card:nth-child(2) { animation-delay: 0.2s; }
.user-card:nth-child(3) { animation-delay: 0.3s; }
.user-card:nth-child(4) { animation-delay: 0.4s; }
.user-card:nth-child(5) { animation-delay: 0.5s; }
.user-card:nth-child(6) { animation-delay: 0.6s; }

.user-card:hover {
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
    transform: translateY(-4px) scale(1.02);
}

.user-card:hover::before {
    left: 100%;
}

.user-card .card-header {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    position: relative;
    z-index: 1;
}

.user-avatar {
    width: 3.5rem;
    height: 3.5rem;
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
    border: 2px solid rgba(255, 255, 255, 0.8);
}

.user-avatar:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    transform: scale(1.1) rotate(5deg);
    border-color: white;
}

.user-avatar i {
    color: white;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
}

.user-avatar:hover i {
    transform: scale(1.1);
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.status-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.status-dot.admin {
    background: #8b5cf6;
}

.status-dot.tutor {
    background: #10b981;
}

.status-dot.parent {
    background: #f59e0b;
}

.action-menu-btn {
    width: 2rem;
    height: 2rem;
    border: none;
    background: rgba(107, 114, 128, 0.1);
    border-radius: 0.5rem;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-menu-btn:hover {
    background: rgba(107, 114, 128, 0.2);
    color: #374151;
}

.user-card .card-body {
    padding: 1.5rem;
    position: relative;
    z-index: 1;
}

.user-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
    transition: all 0.3s ease;
}

.user-card:hover .user-name {
    color: #3b82f6;
    transform: translateX(4px);
}

.user-email {
    color: #64748b;
    font-size: 0.875rem;
    margin: 0 0 1rem 0;
    transition: all 0.3s ease;
}

.user-card:hover .user-email {
    color: #475569;
    transform: translateX(4px);
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.role-badge:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    transform: scale(1.05) translateY(-1px);
}

.role-badge i {
    transition: transform 0.3s ease;
}

.role-badge:hover i {
    transform: scale(1.1) rotate(10deg);
}

.role-badge.admin {
    background: rgba(139, 92, 246, 0.1);
    color: #7c3aed;
}

.role-badge.tutor {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.role-badge.parent {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.user-card .card-footer {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-top: 1px solid #e2e8f0;
    position: relative;
    z-index: 1;
}

.quick-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.quick-btn {
    width: 2.5rem;
    height: 2.5rem;
    border: none;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.5);
    position: relative;
    overflow: hidden;
}

.quick-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transition: all 0.4s ease;
    transform: translate(-50%, -50%);
}

.quick-btn:hover::before {
    width: 120%;
    height: 120%;
}

.quick-btn.view {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.quick-btn.view:hover {
    background: rgba(59, 130, 246, 0.2);
    color: #1d4ed8;
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    transform: scale(1.1) rotate(5deg);
}

.quick-btn.edit {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.quick-btn.edit:hover {
    background: rgba(245, 158, 11, 0.2);
    color: #b45309;
    box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
    transform: scale(1.1) rotate(-5deg);
}

.quick-btn.delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.quick-btn.delete:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #dc2626;
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
    transform: scale(1.1) rotate(5deg);
}

.quick-btn i {
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.quick-btn:hover i {
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem;
    color: #64748b;
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 2rem;
}

/* Table Container */
.table-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-users-section {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .users-controls {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .controls-left {
        flex-direction: column;
        gap: 1rem;
    }

    .controls-right {
        justify-content: space-between;
    }

    .search-container {
        max-width: none;
    }

    .users-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .export-buttons {
        flex-direction: column;
        width: 100%;
    }

    .export-btn {
        justify-content: center;
    }

    .action-btn span {
        display: none;
    }
}
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable for table view
        let dataTable = null;

        function initializeDataTable() {
            if (dataTable) {
                dataTable.destroy();
            }
            dataTable = initDataTable('usersTable', {
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 4] },
                    { className: 'text-center', targets: [0, 3, 4] }
                ]
            });
        }

        // Enhanced loading overlay
        if (!$('.loading-overlay').length) {
            $('body').append(`
                <div class="loading-overlay">
                    <div class="loading-spinner">
                        <div class="spinner-ring"></div>
                        <div class="spinner-text">Loading...</div>
                    </div>
                </div>
            `);
        }

        // Add smooth hover effects to cards
        $('.user-card').hover(
            function() {
                $(this).addClass('card-hover-enhanced');
            },
            function() {
                $(this).removeClass('card-hover-enhanced');
            }
        );

        // Enhanced button click effects
        $('.action-btn, .quick-btn, .btn-sm, .action-btn-modern').on('mousedown', function(e) {
            const $btn = $(this);
            $btn.addClass('btn-clicked');
            setTimeout(() => {
                $btn.removeClass('btn-clicked');
            }, 150);
        });

        // Enhanced modern table row hover effects
        $('.modern-table-row, .table-row').hover(
            function() {
                $(this).addClass('table-row-enhanced-hover');
            },
            function() {
                $(this).removeClass('table-row-enhanced-hover');
            }
        );



        // Modern badge hover effects
        $('.modern-badge, .role-badge-modern').hover(
            function() {
                $(this).addClass('badge-enhanced-hover');
            },
            function() {
                $(this).removeClass('badge-enhanced-hover');
            }
        );

        // Smooth scroll to table when switching to table view
        $('.view-btn[data-view="table"]').on('click', function() {
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: $('#tableView').offset().top - 100
                }, 500);
            }, 100);
        });

        // Simple View Toggle Functionality
        $('.view-btn').on('click', function() {
            const view = $(this).data('view');

            $('.view-btn').removeClass('active');
            $(this).addClass('active');

            if (view === 'cards') {
                $('#tableView').hide();
                $('#cardView').show();
            } else {
                $('#cardView').hide();
                $('#tableView').show();
                setTimeout(initializeDataTable, 100);
            }
        });

        // Enhanced Search Functionality for Both Views
        $('#userSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();

            // Search in card view
            $('.user-card').each(function(index) {
                const searchData = $(this).data('search');
                const $card = $(this);

                if (searchData.includes(searchTerm)) {
                    if ($card.is(':hidden')) {
                        $card.fadeIn(300);
                    }
                } else {
                    $card.fadeOut(200);
                }
            });

            // Search in table view using DataTable
            if (dataTable) {
                dataTable.search(searchTerm).draw();
            }
        });

        // Role Filter Functionality for Card View
        $('#roleFilter').on('change', function() {
            const selectedRole = $(this).val();

            $('.user-card').each(function() {
                const cardRole = $(this).data('role');
                if (selectedRole === '' || cardRole === selectedRole) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Export Functionality
        $('.export-btn.excel').on('click', function() {
            if ($('#tableView').is(':visible') && dataTable) {
                // Export table data
                exportTableToExcel();
            } else {
                // Export card data
                exportCardData('excel');
            }
        });

        $('.export-btn.pdf').on('click', function() {
            if ($('#tableView').is(':visible') && dataTable) {
                // Export table data
                exportTableToPDF();
            } else {
                // Export card data
                exportCardData('pdf');
            }
        });

        function exportTableToExcel() {
            const tableData = [];
            const headers = ['#', 'User', 'Email', 'Role'];
            tableData.push(headers);

            $('#usersTable tbody tr:visible').each(function() {
                const row = [];
                $(this).find('td').each(function(index) {
                    if (index < 4) { // Skip actions column
                        let cellText = $(this).text().trim();
                        if (index === 1) { // User column
                            cellText = $(this).find('.user-name-modern, .user-name').text().trim();
                        } else if (index === 2) { // Email column
                            cellText = $(this).find('.email-text-modern, .email-text').text().trim();
                        } else if (index === 3) { // Role column
                            cellText = $(this).find('.role-badge-modern, .modern-badge').text().trim();
                        }
                        row.push(cellText);
                    }
                });
                if (row.length > 0) tableData.push(row);
            });

            downloadExcel(tableData, 'users_list.xlsx');
        }

        function exportTableToPDF() {
            window.print();
        }

        function downloadExcel(data, filename) {
            const worksheet = XLSX.utils.aoa_to_sheet(data);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Users');
            XLSX.writeFile(workbook, filename);
        }

        function exportCardData(format) {
            // Create temporary table for export
            const tempTable = $('<table>').addClass('d-none');
            const thead = $('<thead>').append(
                $('<tr>').append(
                    '<th>Sr No</th><th>Name</th><th>Email</th><th>Role</th>'
                )
            );
            const tbody = $('<tbody>');

            $('.user-card:visible').each(function(index) {
                const name = $(this).find('.user-name').text();
                const email = $(this).find('.user-email').text();
                const role = $(this).find('.role-badge').text().trim();

                tbody.append(
                    $('<tr>').append(
                        `<td>${index + 1}</td><td>${name}</td><td>${email}</td><td>${role}</td>`
                    )
                );
            });

            tempTable.append(thead).append(tbody);
            $('body').append(tempTable);

            // Initialize temporary DataTable for export
            const tempDT = tempTable.DataTable({
                dom: 'Bfrtip',
                buttons: [format]
            });

            tempDT.button(`.buttons-${format}`).trigger();

            // Clean up
            setTimeout(() => {
                tempDT.destroy();
                tempTable.remove();
            }, 1000);
        }

        // Refresh Users
        $('#refreshUsers').on('click', function() {
            location.reload();
        });

        // Simple Delete Function
        window.deleteUser = function(userID) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.loading-overlay').addClass('show');

                    $.ajax({
                        url: '{{ route('users.delete', '') }}/' + userID,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('.loading-overlay').removeClass('show');

                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message || 'User deleted successfully.',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message || 'Failed to delete user.',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            $('.loading-overlay').removeClass('show');
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'An unexpected error occurred.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        };

        // Initialize with card view
        $('#cardView').show();
        $('#tableView').hide();
    });

    // Enhanced CSS for animations and effects
    const enhancedCSS = `
        <style>
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            text-align: center;
        }

        .spinner-ring {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(59, 130, 246, 0.2);
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner-text {
            color: #3b82f6;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Enhanced Card Hover State */
        .card-hover-enhanced {
            transform: translateY(-6px) scale(1.03) !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2) !important;
            border-color: #1d4ed8 !important;
        }

        /* Button Click Effect */
        .btn-clicked {
            transform: scale(0.95) !important;
        }

        /* Enhanced Focus States */
        .search-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: scale(1.02);
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Ultra Modern Table Container */
        .ultra-modern-table-container {
            animation: slideUp 0.6s ease-out 0.4s both;
            margin-top: 1rem;
        }

        /* Glass Table Wrapper */
        .glass-table-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-table-wrapper:hover {
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(59, 130, 246, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Modern Table Header */
        .modern-table-header {
            background: linear-gradient(135deg,
                rgba(100, 116, 139, 0.05) 0%,
                rgba(71, 85, 105, 0.05) 100%);
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-left-section {
            flex: 1;
        }

        .table-title-modern {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .title-icon-wrapper {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, #64748b, #475569);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(71, 85, 105, 0.15);
        }

        .title-icon-wrapper i {
            font-size: 2rem;
            color: white;
        }

        .title-content h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #374151;
            margin: 0 0 0.25rem 0;
        }

        .title-content p {
            color: #64748b;
            margin: 0;
            font-size: 0.95rem;
        }

        .header-right-section {
            display: flex;
            gap: 1rem;
        }

        .stats-cards {
            display: flex;
            gap: 1rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.25rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Glass Table Container */
        .glass-table-container {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 0 0 2rem 2rem;
            overflow: hidden;
        }

        /* Ultra Modern Table */
        .ultra-modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
            table-layout: fixed;
        }

        /* Glass Table Head */
        .glass-table-head {
            background: linear-gradient(135deg,
                rgba(100, 116, 139, 0.03) 0%,
                rgba(71, 85, 105, 0.03) 100%);
        }

        .header-row {
            border-bottom: 2px solid rgba(100, 116, 139, 0.1);
        }

        .modern-header-cell {
            padding: 0;
            border: none;
            position: relative;
            background: transparent;
        }

        /* Column width specifications */
        .modern-header-cell:nth-child(1) { width: 8%; }
        .modern-header-cell:nth-child(2) { width: 25%; }
        .modern-header-cell:nth-child(3) { width: 30%; }
        .modern-header-cell:nth-child(4) { width: 15%; }
        .modern-header-cell:nth-child(5) { width: 22%; }

        .modern-table-cell:nth-child(1) { width: 8%; }
        .modern-table-cell:nth-child(2) { width: 25%; }
        .modern-table-cell:nth-child(3) { width: 30%; }
        .modern-table-cell:nth-child(4) { width: 15%; }
        .modern-table-cell:nth-child(5) { width: 22%; }

        .header-cell-content {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .header-cell-content.justify-content-center {
            justify-content: center;
        }

        .header-icon-modern {
            width: 2rem;
            height: 2rem;
            background: linear-gradient(135deg, #64748b, #475569);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            box-shadow: 0 2px 8px rgba(71, 85, 105, 0.15);
        }

        .header-label {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
        }

        /* Glass Table Body */
        .glass-table-body {
            background: rgba(255, 255, 255, 0.4);
        }

        .modern-table-row {
            border: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            backdrop-filter: blur(5px);
        }

        .modern-table-row:hover {
            background: rgba(59, 130, 246, 0.05);
            backdrop-filter: blur(10px);
            transform: scale(1.01);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        }

        .modern-table-row:nth-child(even) {
            background: rgba(248, 250, 252, 0.3);
        }

        .modern-table-cell {
            padding: 0;
            border: none;
            border-bottom: 1px solid rgba(226, 232, 240, 0.2);
            vertical-align: middle;
            position: relative;
        }

        .modern-cell-content {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .modern-cell-content.justify-content-center {
            justify-content: center;
        }

        /* Row Number Modern */
        .row-number-modern {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border: 2px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .row-number-modern {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
            border-color: rgba(14, 165, 233, 0.3);
            transform: scale(1.1);
        }

        /* User Profile Modern */
        .user-profile-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar-modern {
            position: relative;
        }

        .avatar-circle {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, #64748b, #475569);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 16px rgba(71, 85, 105, 0.2);
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .avatar-circle {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 24px rgba(71, 85, 105, 0.3);
        }

        .user-info-modern {
            flex: 1;
        }

        .user-name-modern {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .user-name-modern {
            color: #0ea5e9;
        }

        .user-id-modern {
            font-size: 0.75rem;
            color: #64748b;
            background: rgba(59, 130, 246, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .user-id-modern {
            background: rgba(14, 165, 233, 0.15);
            color: #0ea5e9;
        }

        /* Email Modern */
        .email-modern {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .email-icon-modern {
            width: 2rem;
            height: 2rem;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .email-icon-modern {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .email-text-modern {
            color: #64748b;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .modern-table-row:hover .email-text-modern {
            color: #1e293b;
        }

        /* Role Badge Modern */
        .role-badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .role-badge-modern:hover {
            transform: scale(1.05) translateY(-1px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .badge-icon {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            font-size: 0.75rem;
        }

        .role-badge-modern.role-admin {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
        }

        .role-badge-modern.role-tutor {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
        }

        .role-badge-modern.role-student {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        /* Action Buttons Modern */
        .action-buttons-modern {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn-glass {
            width: 2.75rem;
            height: 2.75rem;
            border: none;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            font-size: 1rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .action-btn-glass::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            transform: translate(-50%, -50%);
        }

        .action-btn-glass:hover::before {
            width: 120%;
            height: 120%;
        }

        .action-btn-glass:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .action-btn-glass.view {
            background: linear-gradient(135deg, #64748b, #475569);
            color: white;
        }

        .action-btn-glass.view:hover {
            box-shadow: 0 6px 20px rgba(100, 116, 139, 0.3);
        }

        .action-btn-glass.edit {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: white;
        }

        .action-btn-glass.edit:hover {
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
            transform: scale(1.1) rotate(-5deg);
        }

        .action-btn-glass.delete {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .action-btn-glass.delete:hover {
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .action-btn-glass i {
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .action-btn-glass:hover i {
            transform: scale(1.1);
        }

        /* Table Header Section */
        .table-header-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .table-title i {
            color: #3b82f6;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .table-title:hover i {
            transform: scale(1.1) rotate(5deg);
            color: #1d4ed8;
        }

        .table-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: rgba(59, 130, 246, 0.15);
            transform: scale(1.05);
        }

        /* Modern Table Responsive */
        .modern-table-responsive {
            overflow-x: auto;
            background: white;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
            table-layout: fixed;
        }

        /* Modern Table Head */
        .modern-table-head {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .table-header-cell {
            padding: 0;
            border: none;
            position: relative;
            background: transparent;
        }

        /* Column width specifications for proper alignment */
        .table-header-cell:nth-child(1) { width: 8%; }  /* # */
        .table-header-cell:nth-child(2) { width: 25%; } /* User */
        .table-header-cell:nth-child(3) { width: 30%; } /* Email */
        .table-header-cell:nth-child(4) { width: 15%; } /* Role */
        .table-header-cell:nth-child(5) { width: 22%; } /* Actions */

        .table-cell:nth-child(1) { width: 8%; }  /* # */
        .table-cell:nth-child(2) { width: 25%; } /* User */
        .table-cell:nth-child(3) { width: 30%; } /* Email */
        .table-cell:nth-child(4) { width: 15%; } /* Role */
        .table-cell:nth-child(5) { width: 22%; } /* Actions */

        .header-content {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .header-content::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transition: width 0.4s ease;
        }

        .header-content:hover::before {
            width: 100%;
        }

        .header-content:hover {
            background: rgba(59, 130, 246, 0.05);
            color: #3b82f6;
        }

        .header-icon {
            font-size: 1.125rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .header-content:hover .header-icon {
            color: #3b82f6;
            transform: scale(1.1);
        }

        .header-text {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #374151;
            transition: color 0.3s ease;
        }

        .header-content:hover .header-text {
            color: #3b82f6;
        }

        /* Modern Table Body */
        .modern-table-body {
            background: white;
        }

        .table-row {
            border: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            animation: slideUp 0.4s ease-out both;
        }

        .table-row:nth-child(1) { animation-delay: 0.1s; }
        .table-row:nth-child(2) { animation-delay: 0.15s; }
        .table-row:nth-child(3) { animation-delay: 0.2s; }
        .table-row:nth-child(4) { animation-delay: 0.25s; }
        .table-row:nth-child(5) { animation-delay: 0.3s; }
        .table-row:nth-child(6) { animation-delay: 0.35s; }
        .table-row:nth-child(7) { animation-delay: 0.4s; }
        .table-row:nth-child(8) { animation-delay: 0.45s; }
        .table-row:nth-child(9) { animation-delay: 0.5s; }
        .table-row:nth-child(10) { animation-delay: 0.55s; }

        .table-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: width 0.4s ease;
            z-index: 1;
        }

        .table-row:hover::before {
            width: 5px;
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(59, 130, 246, 0.08) 100%);
            transform: translateX(10px) scale(1.01);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
            border-radius: 0.75rem;
        }

        .table-row:nth-child(even) {
            background: rgba(248, 250, 252, 0.3);
        }

        .table-row:nth-child(even):hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.02) 0%, rgba(59, 130, 246, 0.08) 100%);
        }

        .table-cell {
            padding: 0;
            border: none;
            border-bottom: 1px solid rgba(226, 232, 240, 0.3);
            vertical-align: middle;
            position: relative;
            z-index: 2;
        }

        .cell-content {
            padding: 1.5rem 2rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .cell-content.justify-content-center {
            justify-content: center;
        }

        .table-row:hover .cell-content {
            color: #1e293b;
        }

        /* Row Number Styling */
        .row-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #64748b;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .table-row:hover .row-number {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            transform: scale(1.1);
        }

        /* User Info Styling */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar-small {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .table-row:hover .user-avatar-small {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }

        .table-row:hover .user-name {
            color: #3b82f6;
            transform: translateX(4px);
        }

        .user-id {
            font-size: 0.75rem;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .table-row:hover .user-id {
            color: #475569;
            transform: translateX(4px);
        }

        /* Email Container Styling */
        .email-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .email-icon {
            color: #64748b;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .table-row:hover .email-icon {
            color: #3b82f6;
            transform: scale(1.1);
        }

        .email-text {
            color: #64748b;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .table-row:hover .email-text {
            color: #1e293b;
            transform: translateX(4px);
        }

        /* Modern Badge Styling */
        .modern-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .modern-badge:hover {
            transform: scale(1.05) translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .modern-badge i {
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }

        .modern-badge:hover i {
            transform: scale(1.1) rotate(10deg);
        }

        .modern-badge.role-admin {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .modern-badge.role-tutor {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .modern-badge.role-student {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        /* Action Buttons Modern */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn-modern {
            width: 2.5rem;
            height: 2.5rem;
            border: none;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .action-btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            transform: translate(-50%, -50%);
        }

        .action-btn-modern:hover::before {
            width: 120%;
            height: 120%;
        }

        .action-btn-modern:hover {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .action-btn-modern.view {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .action-btn-modern.view:hover {
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .action-btn-modern.edit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .action-btn-modern.edit:hover {
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
            transform: scale(1.15) rotate(-5deg);
        }

        .action-btn-modern.delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .action-btn-modern.delete:hover {
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        .action-btn-modern i {
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .action-btn-modern:hover i {
            transform: scale(1.1);
        }

        /* Empty State Table */
        .empty-row {
            border: none;
        }

        .empty-cell {
            padding: 0;
            border: none;
        }

        .empty-state-table {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }

        .empty-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .empty-state-table:hover .empty-icon {
            color: #3b82f6;
            transform: scale(1.1);
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .empty-subtitle {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Responsive Table Design */
        @media (max-width: 768px) {
            .enhanced-table-wrapper {
                border-radius: 1rem;
                margin: 0 0.5rem;
            }

            .table-header-section {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .header-content {
                padding: 1rem 1.5rem;
                font-size: 0.875rem;
            }

            .cell-content {
                padding: 1rem 1.5rem;
            }

            .user-info {
                gap: 0.75rem;
            }

            .user-avatar-small {
                width: 2rem;
                height: 2rem;
                font-size: 1rem;
            }

            .action-btn-modern {
                width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }

            .modern-badge {
                padding: 0.4rem 0.75rem;
                font-size: 0.7rem;
            }
        }

        /* Enhanced Table Badges */
        .table .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .table .badge:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .table .badge.bg-primary {
            background: linear-gradient(135deg, #3b82f6, #1e40af) !important;
            color: white;
        }

        .table .badge.bg-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: white;
        }

        .table .badge.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            color: white;
        }

        .table .badge.bg-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white;
        }

        /* Enhanced Table Action Buttons */
        .table .btn-sm {
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin: 0 0.125rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .table .btn-sm::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .table .btn-sm:hover::before {
            width: 120%;
            height: 120%;
        }

        .table .btn-sm:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .table .btn-outline-primary {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .table .btn-outline-primary:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #1d4ed8;
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .table .btn-outline-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .table .btn-outline-warning:hover {
            background: rgba(245, 158, 11, 0.2);
            color: #b45309;
            border-color: #f59e0b;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            transform: scale(1.1) rotate(-5deg);
        }

        .table .btn-outline-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .table .btn-outline-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #dc2626;
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Enhanced Table Row Animation */
        .table tbody tr {
            animation: slideUp 0.4s ease-out both;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(6) { animation-delay: 0.35s; }
        .table tbody tr:nth-child(7) { animation-delay: 0.4s; }
        .table tbody tr:nth-child(8) { animation-delay: 0.45s; }
        .table tbody tr:nth-child(9) { animation-delay: 0.5s; }
        .table tbody tr:nth-child(10) { animation-delay: 0.55s; }

        /* Enhanced Pagination */
        .pagination {
            margin: 0;
            padding: 1rem 0;
        }

        .page-link {
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 0.75rem 1rem;
            margin: 0 0.125rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background: white;
        }

        .page-link:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            border-color: #3b82f6;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .page-item.disabled .page-link {
            background: #f8fafc;
            color: #cbd5e1;
            border-color: #e2e8f0;
        }

        /* Enhanced Modern Table Interaction Classes */
        .table-row-enhanced-hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.12) 100%) !important;
            transform: translateX(15px) scale(1.02) !important;
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.25) !important;
            border-radius: 1rem !important;
        }

        .header-enhanced-hover {
            background: rgba(59, 130, 246, 0.08) !important;
            color: #1d4ed8 !important;
        }

        .badge-enhanced-hover {
            transform: scale(1.1) translateY(-2px) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2) !important;
        }

        /* Enhanced DataTable Styling */
        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            margin: 1rem 0;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        .dataTables_length select:focus,
        .dataTables_filter input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Enhanced Table Empty State */
        .table tbody tr td[colspan] {
            text-align: center;
            padding: 3rem;
            color: #64748b;
            font-size: 1rem;
        }

        /* Enhanced Table Loading State */
        .table-loading {
            position: relative;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        /* Enhanced Table Responsive */
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 0.75rem;
                margin: 0 0.5rem;
            }

            .table thead th,
            .table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }

            .table .btn-sm {
                padding: 0.375rem;
                margin: 0 0.0625rem;
            }
        }

        /* Enhanced export button effects */
        .export-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* View toggle button enhancements */
        .view-btn {
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .view-btn.active {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
        }

        /* Enhanced alert hover */
        .modern-alert:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }
        </style>
    `;

    $('head').append(enhancedCSS);
</script>
@endpush

@endsection