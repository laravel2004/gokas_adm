    <?php

    use App\Http\Controllers\Admin\Tracking\TrackingController;
    use App\Http\Controllers\Admin\Transaction\TransactionController;
    use App\Http\Controllers\Approval\Auth\ApprovalAuthController;
    use App\Http\Controllers\Approval\RequestApproval\RequestApprovalController;
    use App\Http\Controllers\Driver\Auth\DriverAuthController;
    use App\Http\Controllers\Driver\DriverTask\DriverTaskDriverController;
    use App\Http\Controllers\User\Auth\UserAuthController;
    use App\Http\Controllers\User\Invoice\InvoiceController;
    use App\Http\Controllers\User\Loan\LoanController;
    use App\Http\Controllers\User\Paylater\PaylaterController;
    use App\Http\Controllers\User\Payment\PaymentController;
    use App\Http\Middleware\DriverAuthMiddleware;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::prefix('/v1')->group(function () {
        Route::prefix('/auth')->group(function () {
            Route::post('/login', [UserAuthController::class, 'login']);
            Route::get('/me', [UserAuthController::class, 'getInfo'])->middleware([DriverAuthMiddleware::class]);
            Route::get('/check', [UserAuthController::class, 'me'])->middleware([DriverAuthMiddleware::class]);
            Route::post('/logout', [UserAuthController::class, 'logout'])->middleware([DriverAuthMiddleware::class]);
            Route::post('/change-password', [UserAuthController::class, 'changePassword'])->middleware([DriverAuthMiddleware::class]);
        });

        Route::prefix('/invoice')->middleware([DriverAuthMiddleware::class])->group(function () {
           Route::get('/', [InvoiceController::class, 'index']);
           Route::get('download/{id}', [InvoiceController::class, 'download']);
        });

        Route::prefix('/paylater')->middleware([DriverAuthMiddleware::class])->group(function () {
            Route::get('/', [PaylaterController::class, 'index']);
            Route::post('/request-payment', [PaymentController::class, 'payment']);
        });

        Route::prefix('/loan')->middleware([DriverAuthMiddleware::class])->group(function () {
            Route::get('/', [LoanController::class, 'index']);
            Route::post('/store', [LoanController::class, 'store']);
        });


        Route::post('/transaction/store', [PaymentController::class, 'store']);
        Route::post('/request-payment', [PaymentController::class, 'payment']);
        Route::post('/request-payment/compare', [PaymentController::class, 'comparePayment']);
    });

    Route::prefix('/v1')->group(function () {

        Route::prefix('/approval')->group(function () {
            Route::prefix('/auth')->group(function () {
                Route::post('/login', [ApprovalAuthController::class, 'login']);
                Route::get('/me', [ApprovalAuthController::class, 'me'])->middleware([DriverAuthMiddleware::class]);
            });

            Route::prefix('/request')->group(function () {
                Route::get('/', [RequestApprovalController::class, 'index'])->middleware([DriverAuthMiddleware::class]);
                Route::post('/approve/{id}', [RequestApprovalController::class, 'approve'])->middleware([DriverAuthMiddleware::class]);
                Route::post('/reject/{id}', [RequestApprovalController::class, 'reject'])->middleware([DriverAuthMiddleware::class]);
            });
        });
    });
