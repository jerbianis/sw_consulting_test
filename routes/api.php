<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




/** ******************************* **/
/******* Authorisation routes *******/
/** ***************************** **/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');

    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});





/** ******************************************* **/
/******* Job and Application logic routes *******/
/** ***************************************** **/

Route::middleware(['auth:sanctum','verified'])->group(function () {
    
    // User routes
    Route::middleware('is_candidate')->group(function () {
        Route::post('/cv', [UserController::class, 'create_cv']);
        Route::get('/jobs', [UserController::class, 'index']);
        Route::post('/apply/{job_post}', [UserController::class, 'apply']);
    });

    // Admin routes
    Route::middleware('is_admin')->group(function () {
        Route::post('/admin/jobs', [AdminController::class, 'store']);
        Route::put('/admin/jobs/{job_post}', [AdminController::class, 'update']);
        Route::delete('/admin/jobs/{job_post}', [AdminController::class, 'destroy']);
        Route::get('/admin/applications', [AdminController::class, 'index']);
        Route::get('/admin/applications/{application}/download', [AdminController::class, 'download_cv']);
    });

});
