<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Payroll Admin</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-white bg-white shadow-sm mb-4">
    <div class="container">
        <img src="{{ asset('images/Payroll logo.png') }}" height="50" alt="Logo">
        <span class="fw-bold">Payroll System - Admin Panel</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Payroll Records</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Net Pay</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $p)
                            <tr>
                                <td>{{ $p->employee->name }}</td>
                                <td>₱{{ number_format($p->net_pay, 2) }}</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <form action="{{ route('payroll.archive', $p->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Archive</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>