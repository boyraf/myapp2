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
use App\Http\Controllers\AdminSharesController;
use App\Http\Controllers\MemberSharesController;
use App\Http\Controllers\MemberGuarantorsController;
use App\Http\Controllers\AdminGuarantorsController;

/*
|-------------------------------------------------------------------------- 
| Public Routes
|-------------------------------------------------------------------------- 
*/

// Welcome Page (use views under resources/views/home/)
Route::get('/', function () {
    return view('home.home');
});

// Simple static pages (use views/home/*)
Route::get('/about', function () {
    return view('home.about');
});

Route::get('/services', function () {
    return view('home.services');
});

Route::get('/contact', function () {
    return view('home.contact');
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

    // Guarantors (admin view)
    Route::prefix('loans/{loan}/guarantors')->group(function () {
        Route::get('/', [AdminGuarantorsController::class, 'index'])->name('loans.guarantors');
        Route::delete('{id}', [AdminGuarantorsController::class, 'destroy'])->name('loans.guarantors.destroy');
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

    // Shares (Admin controlled)
    Route::prefix('shares')->group(function () {
        Route::get('/', [AdminSharesController::class, 'index'])->name('shares');
        Route::get('/create', [AdminSharesController::class, 'create'])->name('shares.create');
        Route::post('/', [AdminSharesController::class, 'store'])->name('shares.store');
        Route::get('{id}/edit', [AdminSharesController::class, 'edit'])->name('shares.edit');
        Route::put('{id}', [AdminSharesController::class, 'update'])->name('shares.update');
        Route::delete('{id}', [AdminSharesController::class, 'destroy'])->name('shares.destroy');
        Route::post('distribute', [AdminSharesController::class, 'distributeDividends'])->name('shares.distribute');
    });

    // Transactions
    Route::get('transactions', [TransactionsController::class, 'index'])->name('transactions');

    // Audit Logs
    Route::get('auditlogs', [AuditLogsController::class, 'index'])->name('auditlogs');

});

// Protected Member Routes (auth:member)
Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MembersController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/savings', [MembersController::class, 'dashboardSavings'])->name('dashboard.savings');
    Route::get('/dashboard/loans', [MembersController::class, 'dashboardLoans'])->name('dashboard.loans');
    Route::get('/dashboard/limit', [MembersController::class, 'dashboardLimit'])->name('dashboard.limit');
    Route::get('/dashboard/pending', [MembersController::class, 'dashboardPending'])->name('dashboard.pending');
    
    // Savings
    Route::get('/savings/statement', [SavingsController::class, 'statement'])->name('savings.statement');
    Route::get('/savings/deposit', [SavingsController::class, 'deposit'])->name('savings.deposit');
    Route::post('/savings/deposit', [SavingsController::class, 'storeDeposit'])->name('savings.store');
    Route::get('/savings/withdraw', [SavingsController::class, 'withdraw'])->name('savings.withdraw');
    Route::post('/savings/withdraw', [SavingsController::class, 'storeWithdraw'])->name('savings.storeWithdraw');
    
    // Loans
    Route::get('/loans/apply', [LoansController::class, 'apply'])->name('loans.apply');
    Route::post('/loans/apply', [LoansController::class, 'storeApply'])->name('loans.storeApply');
    Route::get('/loans/history', [LoansController::class, 'history'])->name('loans.history');
    Route::get('/loans/schedule', [LoansController::class, 'schedule'])->name('loans.schedule');
    
    // Transactions
    Route::get('/transactions/recent', [TransactionsController::class, 'recent'])->name('transactions.recent');
    Route::get('/transactions/download', [TransactionsController::class, 'download'])->name('transactions.download');
    
    // Repayments
    Route::get('/repayments/history', [RepaymentsController::class, 'history'])->name('repayments.history');
    Route::get('/repayments/schedule', [RepaymentsController::class, 'schedule'])->name('repayments.schedule');
    Route::get('/repayments/make', [RepaymentsController::class, 'make'])->name('repayments.make');
    Route::post('/repayments/make', [RepaymentsController::class, 'storeMake'])->name('repayments.storeMake');
    
    // Profile
    Route::get('/profile/view', [MembersController::class, 'profileView'])->name('profile.view');
    Route::get('/profile/update', [MembersController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/profile/update', [MembersController::class, 'profileStore'])->name('profile.store');
    Route::get('/profile/password', [MembersController::class, 'passwordForm'])->name('profile.password');
    Route::put('/profile/password', [MembersController::class, 'passwordStore'])->name('profile.passwordStore');
    
    // Support
    Route::get('/support/contact', [MembersController::class, 'contactForm'])->name('support.contact');
    Route::post('/support/contact', [MembersController::class, 'contactStore'])->name('support.store');
    Route::get('/support/faq', [MembersController::class, 'faq'])->name('support.faq');
    
    // Shares (Member)
    Route::get('/shares', [MemberSharesController::class, 'index'])->name('shares.index');
    Route::get('/shares/buy', [MemberSharesController::class, 'buy'])->name('shares.buy');
    Route::post('/shares/buy', [MemberSharesController::class, 'storeBuy'])->name('shares.buy.store');
    Route::get('/shares/sell', [MemberSharesController::class, 'sell'])->name('shares.sell');
    Route::post('/shares/sell', [MemberSharesController::class, 'storeSell'])->name('shares.sell.store');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Guarantors (member actions on their loans)
    Route::post('/loans/{loan}/guarantors', [MemberGuarantorsController::class, 'store'])->name('loans.guarantors.store');
    Route::delete('/loans/{loan}/guarantors/{id}', [MemberGuarantorsController::class, 'destroy'])->name('loans.guarantors.destroy');
});
