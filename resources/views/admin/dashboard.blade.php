<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Payroll Dashboard</title>
    <style>
        .table-scrollable {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-scrollable thead th {
            position: sticky;
            top: 0;
            background-color: #343a40; 
            color: white;
            z-index: 1;
        }
        .table-scrollable .roster-header th {
            background-color: #0dcaf0; 
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-white bg-white shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <img src="{{ asset('images/Payroll logo.png') }}" height="50" alt="Logo">
            <span class="fw-bold ms-2">Payroll System - Admin Panel</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Log Out ({{ Auth::user()->name }})</button>
        </form>
    </div>
</nav>

<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">Generate Payroll</button>
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#calendarModal">Attendance</button>
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#archivedModal">View Archived</button>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Recent Payrolls / Payslips</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-scrollable">
                        <table class="table table-hover align-middle mb-0">
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
                                    <td>{{ $p->user->name ?? 'Unknown' }}</td>
                                    <td class="fw-bold text-success">₱{{ number_format($p->net_pay, 2) }}</td>
                                    <td>
                                        @if($p->status === 'Paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->status === 'Pending')
                                            <form action="{{ route('payroll.approve', $p->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success me-1" onclick="return confirm('Handed out the cash? Mark as Paid?');">✅ Approve</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('employee.show', $p->user_id) }}" class="btn btn-sm btn-outline-info me-1">View Details</a>
                                        
                                        <form action="{{ route('payroll.archive', $p->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Archive this record?');">Archive</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($payrolls->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No payroll records generated yet.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Company's Employees</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-scrollable">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="roster-header">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Basic Salary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
                                <tr>
                                    <td class="fw-bold">{{ $emp->name }}</td>
                                    <td>{{ $emp->email }}</td>
                                    <td>{{ $emp->position ?? '--' }}</td>
                                    <td>₱{{ number_format($emp->basic_salary, 2) }}</td>
                                    <td>
                                        <a href="{{ route('employee.show', $emp->id) }}" class="btn btn-sm btn-outline-dark">View Details</a>
                                    </td>
                                </tr>
                                @endforeach
                                @if($employees->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No employees found in the database.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">Add New Employee Record</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-2"><label>Full Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-2"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-2"><label>Basic Salary (₱)</label><input type="number" step="0.01" name="basic_salary" class="form-control" required></div>
                <div class="mb-2"><label>Position / Job Title</label><input type="text" name="position" class="form-control"></div>
                <div class="mb-2"><label>Sex</label>
                    <select name="sex" class="form-select">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-2"><label>Address</label><input type="text" name="address" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">Save Employee</button></div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Generate Payroll</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('payroll.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info small">Basic salary will be automatically pulled from the employee's profile. Deductions will apply based on the date.</div>
                <div class="mb-3">
                    <label class="fw-bold">Select Employee:</label>
                    <select name="user_id" class="form-select" required>
                        <option value="" disabled selected>Choose Employee...</option>
                        @foreach($employees as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="fw-bold">Payroll Date:</label><input type="date" name="payroll_date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="mb-3"><label class="fw-bold">Additional Overtime Pay (₱):</label><input type="number" step="0.01" name="overtime" class="form-control" value="0"></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Generate Pay</button></div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title">Add Calendar Log</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3"><label>Employee:</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($employees as $emp) <option value="{{ $emp->id }}">{{ $emp->name }}</option> @endforeach
                    </select>
                </div>
                <div class="mb-3"><label>Date:</label><input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}"></div>
                <div class="mb-3"><label>Time In:</label><input type="time" name="time_in" class="form-control"></div>
                <div class="mb-3"><label>Status:</label>
                    <select name="status" class="form-select" required>
                        <option value="Present">Present</option><option value="Late">Late</option><option value="Absent">Absent</option><option value="Sick">Sick Leave</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-dark">Save Log</button></div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="archivedModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title">Archived Records</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered align-middle">
                <thead><tr><th>Employee</th><th>Net Pay</th><th>Archived Date</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($archived_payrolls as $archived)
                    <tr>
                        <td>{{ $archived->user->name ?? 'Unknown' }}</td>
                        <td>₱{{ number_format($archived->net_pay, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($archived->deleted_at)->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('payroll.restore', $archived->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                </form>

                                <form action="{{ route('payroll.forceDelete', $archived->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete the record from the database forever!');">Delete Forever</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($archived_payrolls->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center text-muted">No archived records.</td>
                    </tr>
                    @endif
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