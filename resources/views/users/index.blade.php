<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .user-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .btn-logout {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.4);
            color: white;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            text-align: center;
            border: none;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }

        .table tbody td {
            border-color: #e9ecef;
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-users me-3"></i>Users Management
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                <div class="col-md-4 text-end">
                    <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-users text-primary mb-3" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $users->count() }}</div>
                    <h6 class="text-muted mb-0">Total Users</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-user-check text-success mb-3" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
                    <h6 class="text-muted mb-0">Verified Users</h6>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-user-clock text-warning mb-3" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $users->where('email_verified_at', null)->count() }}</div>
                    <h6 class="text-muted mb-0">Pending Verification</h6>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-user me-2"></i>User</th>
                            <th><i class="fa-solid fa-truck"></i>Tier</th>
                            <th><i class="fas fa-shield-alt me-2"></i>Status</th>
                            <th><i class="fas fa-calendar me-2"></i>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">#{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">User ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    {{ $user->loyaltyTier() }}
                                </div>
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Verified
                                </span>
                                @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-calendar text-muted me-2"></i>
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted">No users found</h5>
                                <p class="text-muted">There are no users in the system yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($users->count() > 0)
        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Total Records</h6>
                            <h4 class="text-primary mb-0">{{ $users->count() }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Active Users</h6>
                            <h4 class="text-success mb-0">{{ $users->where('email_verified_at', '!=', null)->count() }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">Recent Joins</h6>
                            <h4 class="text-info mb-0">{{ $users->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h4 class="text-warning mb-0">{{ $users->where('created_at', '>=', now()->subMonth())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>