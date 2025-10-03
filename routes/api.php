<?php

use App\Http\Controllers\Mobile\AccountVerificationController;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\Services\SBController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mobile/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/mobile/update-identity', [AccountVerificationController::class, 'updateIdentity']);
    Route::post('/mobile/update-address', [AccountVerificationController::class, 'updateAddress']);
    Route::post('/mobile/submit-id', [AccountVerificationController::class, 'submitId']);

    Route::get('/mobile/services/sb/ordinance/get-folder', [SBController::class, 'getOrdinanceFolder']);
    Route::get('/mobile/services/sb/ordinance/get-pdf/{id}', [SBController::class, 'getOrdinancePdf']);
    Route::get('/mobile/services/sb/ordinance/preview-pdf/{id}', [SBController::class, 'previewOrdinancePdf']);

    Route::post('/mobile/change-password', [AuthController::class, 'changePassword']);
    Route::get('/mobile/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::post('/mobile/login', [AuthController::class, 'login']);
    Route::post('/mobile/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/mobile/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/mobile/create-account', [AuthController::class, 'createAccount']);
    Route::post('/mobile/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/mobile/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/mobile/create-password', [AuthController::class, 'createPassword']);
});
