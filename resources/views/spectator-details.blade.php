<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{ $employee->name }} - Records</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-info shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">👔 Employee Record: {{ $employee->name }}</span>
        <a href="{{ route('spectator.list') }}" class="btn btn-sm btn-light">Exit</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white"><h5 class="mb-0">My Payslips</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light"><tr><th>Date Issued</th><th>Basic Salary</th><th>Overtime</th><th>Deductions</th><th>Net Pay</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($payrolls as $p)
                                <tr>
                                    <td>{{ $p->created_at->format('M d, Y') }}</td>
                                    <td>₱{{ number_format($p->basic_salary, 2) }}</td>
                                    <td>₱{{ number_format($p->overtime, 2) }}</td>
                                    <td class="text-danger">- ₱{{ number_format($p->deductions + (($p->tax_percentage / 100) * $p->basic_salary), 2) }}</td>
                                    <td class="fw-bold text-success">₱{{ number_format($p->net_pay, 2) }}</td>
                                    <td>
                                        @if($p->status === 'Paid') <span class="badge bg-success">Paid</span> @else <span class="badge bg-warning text-dark">Pending</span> @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white"><h5 class="mb-0">My Attendance Logs</h5></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead><tr><th>Date</th><th>Time In</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($attendances as $log)
                            <tr><td>{{ \Carbon\Carbon::parse($log->date)->format('F d, Y') }}</td><td>{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : '--:--' }}</td><td>{{ $log->status }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>