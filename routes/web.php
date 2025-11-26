<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\RepaymentsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\AuditLogsController;

/*
|-------------------------------------------------------------------------- 
| Public Routes
|-------------------------------------------------------------------------- 
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Member Signup & Login
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Login
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

/*
|-------------------------------------------------------------------------- 
| Protected Admin Routes (auth:admin)
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Members
    Route::prefix('members')->group(function () {
        Route::get('/', [MembersController::class, 'index'])->name('members');
        Route::get('/create', [MembersController::class, 'create'])->name('members.create');
        Route::post('/', [MembersController::class, 'store'])->name('members.store');
        Route::get('{id}/edit', [MembersController::class, 'edit'])->name('members.edit');
        Route::put('{id}', [MembersController::class, 'update'])->name('members.update');

        // Toggle status (Activate/Deactivate)
        Route::match(['put','patch'], '{id}/status', [MembersController::class, 'toggleStatus'])
            ->name('members.toggleStatus');
    });

    // Loans
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoansController::class, 'index'])->name('loans');
        Route::get('/create', [LoansController::class, 'create'])->name('loans.create');
        Route::post('/', [LoansController::class, 'store'])->name('loans.store');
        Route::get('{id}/edit', [LoansController::class, 'edit'])->name('loans.edit');
        Route::put('{id}', [LoansController::class, 'update'])->name('loans.update');
        Route::match(['put','patch'], '{id}/status', [LoansController::class, 'toggleStatus'])->name('loans.toggleStatus');
    });

    // Savings
    Route::prefix('savings')->group(function () {
        Route::get('/', [SavingsController::class, 'index'])->name('savings');
        Route::get('/create', [SavingsController::class, 'create'])->name('savings.create');
        Route::post('/', [SavingsController::class, 'store'])->name('savings.store');
        Route::get('{id}/edit', [SavingsController::class, 'edit'])->name('savings.edit');
        Route::put('{id}', [SavingsController::class, 'update'])->name('savings.update');
        Route::match(['put','patch'], '{id}/status', [SavingsController::class, 'toggleStatus'])->name('savings.toggleStatus');
    });

    // Repayments
    Route::prefix('repayments')->group(function () {
        Route::get('/', [RepaymentsController::class, 'index'])->name('repayments');
        Route::get('/create', [RepaymentsController::class, 'create'])->name('repayments.create');
        Route::post('/', [RepaymentsController::class, 'store'])->name('repayments.store');
        Route::get('{id}/edit', [RepaymentsController::class, 'edit'])->name('repayments.edit');
        Route::put('{id}', [RepaymentsController::class, 'update'])->name('repayments.update');
        Route::match(['put','patch'], '{id}/status', [RepaymentsController::class, 'toggleStatus'])->name('repayments.toggleStatus');
    });

    // Transactions
    Route::get('transactions', [TransactionsController::class, 'index'])->name('transactions');

    // Audit Logs
    Route::get('auditlogs', [AuditLogsController::class, 'index'])->name('auditlogs');
});
