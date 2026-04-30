<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Employee Record</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-white bg-white shadow-sm mb-4">
    <div class="container">
        <img src="{{ asset('images/Payroll logo.png') }}" height="50" alt="Logo">
        <span class="fw-bold">Employee Information</span>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">&larr; Back to Dashboard</a>
        
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">Employee Profile</button>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h5 class="mb-0">{{ $employee->name }} - Overall Record</h5>
            <span class="badge bg-light text-dark">Current Salary: ₱{{ number_format($employee->basic_salary, 2) }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="p-3 bg-primary text-white rounded shadow-sm text-center">
                        <h6>Present</h6><h2>{{ $attendance_count }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-danger text-white rounded shadow-sm text-center">
                        <h6>Absences</h6><h2>{{ $absences }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-info text-white rounded shadow-sm text-center">
                        <h6>Sick Leaves</h6><h2>{{ $sick_leaves }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 bg-warning text-dark rounded shadow-sm text-center">
                        <h6>Late Deductions</h6><h2>₱{{ number_format($late_deductions, 2) }}</h2>
                    </div>
                </div>
            </div>

            <hr>
            
            <h5 class="mt-4">Recent Time Logs</h5>
            <table class="table table-bordered table-striped mt-2">
                <thead><tr><th>Date</th><th>Time In</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($employee->attendances as $log)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($log->date)->format('F d, Y') }}</td>
                            <td>{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('h:i A') : '--:--' }}</td>
                            <td>{{ $log->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No attendance logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Employee Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-body">
            <div class="mb-2"><label>Full Name</label><input type="text" name="name" class="form-control" value="{{ $employee->name }}" required></div>
            <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $employee->email }}" required></div>
            
            <div class="row">
                <div class="col-md-6 mb-2"><label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2"><label>Basic Salary (₱)</label><input type="number" step="0.01" name="basic_salary" class="form-control" value="{{ $employee->basic_salary }}" required></div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-2"><label>Position</label><input type="text" name="position" class="form-control" value="{{ $employee->position }}"></div>
                <div class="col-md-6 mb-2"><label>Sex</label>
                    <select name="sex" class="form-select">
                        <option value="Male" {{ $employee->sex == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $employee->sex == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            </div>

            <div class="mb-2"><label>Address</label><input type="text" name="address" class="form-control" value="{{ $employee->address }}"></div>
            
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-warning">Update Profile</button></div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>