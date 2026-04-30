<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// NEW: Public Spectator Routes (No Login Required)
Route::get('/employee-mode', [PayrollController::class, 'spectatorList'])->name('spectator.list');
Route::get('/employee-mode/{id}', [PayrollController::class, 'spectatorShow'])->name('spectator.show');

// SECURE ADMIN ROUTES
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Payroll Routes
    Route::get('/dashboard', [PayrollController::class, 'index'])->name('dashboard');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::put('/payroll/{id}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('/payroll/{id}', [PayrollController::class, 'archive'])->name('payroll.archive');
    Route::post('/payroll/restore/{id}', [PayrollController::class, 'restore'])->name('payroll.restore');
    Route::post('/payroll/approve/{id}', [PayrollController::class, 'approve'])->name('payroll.approve');

    // Calendar and Employee Details
    Route::post('/attendance', [PayrollController::class, 'storeAttendance'])->name('attendance.store');
    Route::get('/employee/{id}', [PayrollController::class, 'showEmployee'])->name('employee.show');

    // Add and Update Employee Routes
    Route::post('/employees', [PayrollController::class, 'storeEmployee'])->name('employees.store');
    Route::put('/employees/{id}', [PayrollController::class, 'updateEmployee'])->name('employees.update');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';