<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Employee Public Roster</title>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="{{ asset('images/Payroll logo.png') }}" height="60" alt="Logo">
        <h3 class="mt-2 fw-bold text-info">Employee Records</h3>
        <p class="text-muted">Click your name to view your payslips and attendance.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($employees as $emp)
                            <a href="{{ route('spectator.show', $emp->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $emp->name }}</h5>
                                    <small class="text-muted">{{ $emp->position ?? 'Employee' }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">View Records &rarr;</span>
                            </a>
                        @endforeach
                        @if($employees->isEmpty())
                            <div class="p-4 text-center text-muted">No employees registered yet.</div>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="text-center mt-4">
    @auth
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                🔒 Log Out & Go to Login
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
            🔒 Back to Admin Login
        </a>
    @endauth
</div>
        </div>
    </div>
</div>

</body>
</html>