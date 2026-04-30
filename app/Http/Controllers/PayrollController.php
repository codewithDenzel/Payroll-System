<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PayrollController extends Controller {
    
    public function index() {
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        $payrolls = Payroll::with('user')->latest()->get();
        
        // This line ensures we grab ONLY the employees
        $employees = User::where('role', 'employee')->get(); 
        
        $archived_payrolls = Payroll::onlyTrashed()->with('user')->latest()->get();
        
        return view('dashboard', compact('payrolls', 'employees', 'archived_payrolls'));
    }

    return redirect()->route('spectator.list');
}

    // --- NEW: SPECTATOR MODE FUNCTIONS ---
    public function spectatorList() {
        $employees = User::where('role', 'employee')->get();
        return view('spectator-list', compact('employees'));
    }

    public function spectatorShow($id) {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $payrolls = Payroll::where('user_id', $id)->latest()->get();
        $attendances = Attendance::where('user_id', $id)->latest()->get();
        return view('spectator-details', compact('employee', 'payrolls', 'attendances'));
    }
    // -------------------------------------

    public function storeEmployee(Request $request) {
        if (Auth::user()->role !== 'admin') abort(403);
        $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|unique:users', 'role' => 'required|string', 'basic_salary' => 'required|numeric']);

        User::create([
            'name' => $request->name, 'email' => $request->email, 'password' => Hash::make('password123'),
            'role' => $request->role, 'address' => $request->address, 'sex' => $request->sex,
            'position' => $request->position, 'basic_salary' => $request->basic_salary
        ]);
        return back()->with('success', 'New employee profile created successfully!');
    }

    public function updateEmployee(Request $request, $id) {
        if (Auth::user()->role !== 'admin') abort(403);
        $employee = User::findOrFail($id);
        $employee->update($request->all());
        return back()->with('success', 'Employee profile updated successfully!');
    }

    public function store(Request $request) {
        if (Auth::user()->role !== 'admin') abort(403);
        $request->validate(['user_id' => 'required|exists:users,id']);

        $employee = User::findOrFail($request->user_id);
        $basic = (float)$employee->basic_salary;
        $overtime = (float)($request->overtime ?? 0);
        $other_deductions = (float)($request->deductions ?? 0);
        $tax_percent = 0;

        $payroll_date = $request->payroll_date ? Carbon::parse($request->payroll_date) : now();
        $day = $payroll_date->day;
        $tax_amount = 0;

        if (in_array($day, [5, 20])) {
            $tax_percent = 20; $tax_amount = $basic * 0.20; $other_deductions = 0; 
        } elseif (in_array($day, [15, 30])) {
            $tax_amount = 0; $other_deductions = ($basic * 0.045) + ($basic * 0.02) + ($basic * 0.025);
        }

        $late_penalty = $employee->attendances()->where('status', 'Late')->count() * 50;
        $other_deductions += $late_penalty;
        $net_pay = ($basic + $overtime) - ($tax_amount + $other_deductions);

        Payroll::create([
            'user_id' => $employee->id, 'basic_salary' => $basic, 'overtime' => $overtime,
            'tax_percentage' => $tax_percent, 'deductions' => $other_deductions, 'net_pay' => $net_pay, 'status' => 'Pending'
        ]);
        return back()->with('success', 'Payroll generated (Pending Approval) for ' . $employee->name);
    }

    public function approve($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        Payroll::findOrFail($id)->update(['status' => 'Paid']);
        return back()->with('success', 'Payroll marked as Paid!');
    }

    public function update(Request $request, $id) {
        if (Auth::user()->role !== 'admin') abort(403);
        Payroll::findOrFail($id)->update($request->all());
        return back()->with('success', 'Payroll updated successfully!');
    }

    public function archive($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        Payroll::findOrFail($id)->delete(); 
        return back()->with('success', 'Record safely archived!');
    }

    public function restore($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        Payroll::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Record successfully restored!');
    }

    public function storeAttendance(Request $request) {
        if (Auth::user()->role !== 'admin') abort(403);
        $request->validate(['user_id' => 'required', 'date' => 'required', 'status' => 'required']);
        Attendance::create($request->all());
        return back()->with('success', 'Attendance log saved!');
    }

    public function showEmployee($id) {
        $employee = User::with('attendances')->findOrFail($id);
        $attendance_count = $employee->attendances->where('status', 'Present')->count();
        $absences = $employee->attendances->where('status', 'Absent')->count();
        $sick_leaves = $employee->attendances->where('status', 'Sick')->count();
        $late_deductions = $employee->attendances->where('status', 'Late')->count() * 50; 
        return view('employee-details', compact('employee', 'attendance_count', 'absences', 'sick_leaves', 'late_deductions'));
    }
}