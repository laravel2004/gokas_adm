<?php

use App\Http\Controllers\Admin\Approval\SettingApprovalController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Driver\DriverController;
use App\Http\Controllers\Admin\DriverTask\DriverTaskController;
use App\Http\Controllers\Admin\Employee\EmployeeManagementController;
use App\Http\Controllers\Admin\Invoice\InvoiceController;
use App\Http\Controllers\Admin\Loan\LoanController;
use App\Http\Controllers\Admin\PickPoint\PickPointController;
use App\Http\Controllers\Admin\SettingLimit\SettingLimitController;
use App\Http\Controllers\Admin\Tracking\TrackingController;
use App\Http\Controllers\Admin\Account\AccountController;
use App\Http\Controllers\Admin\Paylater\PaylaterController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('sudut-panel/admin')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('admin.login.post');

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        //Employee Management
        Route::get('/employee', [EmployeeManagementController::class, 'index'])->name('admin.employee.index');
        Route::get('/employee/create', [EmployeeManagementController::class, 'create'])->name('admin.employee.create');
        Route::post('/employee', [EmployeeManagementController::class, 'store'])->name('admin.employee.store');
        Route::get('/employee/{id}/edit', [EmployeeManagementController::class, 'edit'])->name('admin.employee.edit');
        Route::put('/employee/{id}', [EmployeeManagementController::class, 'update'])->name('admin.employee.update');
        Route::delete('/employee/{id}', [EmployeeManagementController::class, 'destroy'])->name('admin.employee.destroy');
        Route::post('/employee/import', [EmployeeManagementController::class, 'storeWithExcel'])->name('admin.employee.import');

        // Setting Limit
        Route::get('/setting-limit', [SettingLimitController::class, 'index'])->name('admin.setting-limit.index');
        Route::post('/setting-limit', [SettingLimitController::class, 'store'])->name('admin.setting-limit.store');
        Route::put('/setting-limit/{id}', [SettingLimitController::class, 'update'])->name('admin.setting-limit.update');
        Route::delete('/setting-limit/{id}', [SettingLimitController::class, 'destroy'])->name('admin.setting-limit.destroy');

        // Account
        Route::get('/account', [AccountController::class, 'index'])->name('admin.account.index');
        Route::get('/account/{id}/edit', [AccountController::class, 'edit'])->name('admin.account.edit');
        Route::put('/account/{id}', [AccountController::class, 'update'])->name('admin.account.update');
        Route::post('/store-balance', [AccountController::class, 'storeBalance'])->name('admin.account.store-balance');

        // Paylater
        Route::get('/paylater', [PaylaterController::class, 'index'])->name('admin.paylater.index');
        Route::get('paylater/need-paid', [PaylaterController::class, 'needPaid'])->name('admin.paylater.need-paid');
        Route::get('/paylater/account/{id}', [PaylaterController::class, 'show'])->name('admin.paylater.show');
        Route::post('/paylater/paid-off/{id}', [PaylaterController::class, 'paidOff'])->name('admin.paylater.paid-off');
        Route::post('/paylater/bulk-paid-off/{id}', [PaylaterController::class, 'bulkPaidOff'])->name('admin.paylater.bulk-paid-off');

        // Transaction
        Route::get('/transaction', [TransactionController::class, 'index'])->name('admin.transaction.index');

        // Invoice
        Route::get('/invoice', [InvoiceController::class, 'index'])->name('admin.invoice.index');
        Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('admin.invoice.show');
        Route::get('/invoice/download/{id}', [InvoiceController::class, 'download'])->name('admin.invoice.download');

        // Setting Approval
        Route::get('/setting-approval', [SettingApprovalController::class, 'index'])->name('admin.setting-approval.index');
        Route::get('/setting-approval/{id}', [SettingApprovalController::class, 'show'])->name('admin.setting-approval.show');
        Route::delete('/setting-approval/{id}', [SettingApprovalController::class, 'destroy'])->name('admin.setting-approval.destroy');
        Route::post('/setting-approval', [SettingApprovalController::class, 'store'])->name('admin.setting-approval.store');
        Route::get('/approval/check/{id}', [SettingApprovalController::class, 'check'])->name('admin.setting-approval.check');

        // Loan
        Route::get('/loan', [LoanController::class, 'index'])->name('admin.loan.index');
        Route::post('/loan', [LoanController::class, 'store'])->name('admin.loan.store');
        Route::get('/loan/create', [LoanController::class, 'create'])->name('admin.loan.create');
        Route::get('/loan/{id}', [LoanController::class, 'show'])->name('admin.loan.show');
        Route::post('/loan/reject/{id}', [LoanController::class, 'reject'])->name('admin.loan.reject');
        Route::post('/loan/approve/{id}', [LoanController::class, 'bypassApprover'])->name('admin.loan.approve');
        Route::post('/loan/cashout/{id}', [LoanController::class, 'cashOut'])->name('admin.loan.cashout');
        Route::post('/loan/pay/{id}', [LoanController::class, 'payInstalment'])->name('admin.loan.pay');

        // Driver
        Route::get('/driver', [DriverController::class, 'index'])->name('admin.driver');
        Route::post('/driver', [DriverController::class, 'store'])->name('admin.driver.store');
        Route::put('/driver/{id}', [DriverController::class, 'update'])->name('admin.driver.update');
        Route::delete('/driver/{id}', [DriverController::class, 'destroy'])->name('admin.driver.destroy');

        // Pick Point
        Route::get('/pick-point', [PickPointController::class, 'index'])->name('admin.pick-point');
        Route::post('/pick-point', [PickPointController::class, 'store'])->name('admin.pick-point.store');
        Route::put('/pick-point/{id}', [PickPointController::class, 'update'])->name('admin.pick-point.update');
        Route::delete('/pick-point/{id}', [PickPointController::class, 'destroy'])->name('admin.pick-point.destroy');

        // Driver Task
        Route::get('/driver-task', [DriverTaskController::class, 'index'])->name('admin.driver-task');
        Route::get('/driver-task/create', [DriverTaskController::class, 'create'])->name('admin.driver-task.create');
        Route::post('/driver-task', [DriverTaskController::class, 'store'])->name('admin.driver-task.store');
        Route::put('/driver-task/{id}', [DriverTaskController::class, 'update'])->name('admin.driver-task.update');
        Route::get('/driver-task/{id}/edit', [DriverTaskController::class, 'edit'])->name('admin.driver-task.edit');
        Route::delete('/driver-task/{id}', [DriverTaskController::class, 'destroy'])->name('admin.driver-task.destroy');

        // Tracking
        Route::get('/tracking/{id}', [TrackingController::class, 'index'])->name('admin.tracking');
    });
});
