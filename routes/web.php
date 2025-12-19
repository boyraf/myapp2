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
use App\Http\Controllers\ReportController;
use App\Http\Middleware\PreventBackHistory;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('home.home');
})->name('home'); // <-- Named route for logout redirect

// Static pages
Route::get('/about', function () {
    return view('home.about');
})->name('about');

Route::get('/services', function () {
    return view('home.services');
})->name('services');

Route::get('/contact', function () {
    return view('home.contact');
})->name('contact');

// Member Signup & Login
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

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
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Members
    Route::prefix('members')->group(function () {
        Route::get('/', [MembersController::class, 'index'])->name('members');
        Route::get('/create', [MembersController::class, 'create'])->name('members.create');
        Route::post('/', [MembersController::class, 'store'])->name('members.store');
        Route::get('{id}/edit', [MembersController::class, 'edit'])->name('members.edit');
        Route::put('{id}', [MembersController::class, 'update'])->name('members.update');
        Route::match(['put','patch'], '{id}/status', [MembersController::class, 'toggleStatus'])->name('members.toggleStatus');
    });

    // Loans
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoansController::class, 'index'])->name('loans');
        Route::get('/create', [LoansController::class, 'create'])->name('loans.create');
        Route::post('/', [LoansController::class, 'store'])->name('loans.store');
        Route::get('{id}/edit', [LoansController::class, 'edit'])->name('loans.edit');
        Route::put('{id}', [LoansController::class, 'update'])->name('loans.update');
        Route::match(['put','patch'], '{id}/status', [LoansController::class, 'toggleStatus'])->name('loans.toggleStatus');
        Route::get('/pending', [LoansController::class, 'pending'])->name('loans.pending');
        Route::post('{id}/approve', [LoansController::class, 'approve'])->name('loans.approve');
        Route::post('{id}/reject', [LoansController::class, 'reject'])->name('loans.reject');
    });

    // Guarantors
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

    // Shares
    Route::prefix('shares')->group(function () {
        Route::get('/', [AdminSharesController::class, 'index'])->name('shares');
        Route::get('/create', [AdminSharesController::class, 'create'])->name('shares.create');
        Route::post('/', [AdminSharesController::class, 'store'])->name('shares.store');
        Route::get('{id}/edit', [AdminSharesController::class, 'edit'])->name('shares.edit');
        Route::put('{id}', [AdminSharesController::class, 'update'])->name('shares.update');
        Route::delete('{id}', [AdminSharesController::class, 'destroy'])->name('shares.destroy');
        Route::post('distribute', [AdminSharesController::class, 'distributeDividends'])->name('shares.distribute');
    });

    // Transactions & Audit Logs
    Route::get('transactions', [TransactionsController::class, 'index'])->name('transactions');
    Route::get('auditlogs', [AuditLogsController::class, 'index'])->name('auditlogs');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/loans', [ReportController::class, 'loanStatement'])->name('loans');
        Route::get('/loans/export-csv', [ReportController::class, 'exportLoanCSV'])->name('loans.csv');
        
        Route::get('/repayments', [ReportController::class, 'repaymentSchedule'])->name('repayments');
        Route::get('/repayments/export-csv', [ReportController::class, 'exportRepaymentCSV'])->name('repayments.csv');
        
        Route::get('/shares', [ReportController::class, 'shareHoldings'])->name('shares');
        Route::get('/shares/export-csv', [ReportController::class, 'exportSharesCSV'])->name('shares.csv');
        
        Route::get('/dividends', [ReportController::class, 'dividendStatement'])->name('dividends');
        Route::get('/dividends/export-csv', [ReportController::class, 'exportDividendCSV'])->name('dividends.csv');
    });
});

/*
|--------------------------------------------------------------------------
| Protected Member Routes (auth:member + prevent-back-history)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:member', PreventBackHistory::class])->prefix('member')->name('member.')->group(function () {

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

    // Shares
    Route::get('/shares', [MemberSharesController::class, 'index'])->name('shares.index');
    Route::get('/shares/buy', [MemberSharesController::class, 'buy'])->name('shares.buy');
    Route::post('/shares/buy', [MemberSharesController::class, 'storeBuy'])->name('shares.buy.store');
    Route::get('/shares/sell', [MemberSharesController::class, 'sell'])->name('shares.sell');
    Route::post('/shares/sell', [MemberSharesController::class, 'storeSell'])->name('shares.sell.store');

    // Guarantors
    Route::post('/loans/{loan}/guarantors', [MemberGuarantorsController::class, 'store'])->name('loans.guarantors.store');
    Route::delete('/loans/{loan}/guarantors/{id}', [MemberGuarantorsController::class, 'destroy'])->name('loans.guarantors.destroy');

    // Logout (member) - only inside member group
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
