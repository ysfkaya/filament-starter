<?php

use App\Http\Livewire\Auth\ConfirmablePasswordController;
use App\Http\Livewire\Auth\EmailVerificationPromptController;
use App\Http\Livewire\Auth\LoginController;
use App\Http\Livewire\Auth\NewPasswordController;
use App\Http\Livewire\Auth\PasswordController;
use App\Http\Livewire\Auth\PasswordResetLinkController;
use App\Http\Livewire\Auth\RegisteredUserController;
use App\Http\Livewire\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', RegisteredUserController::class)->name('register');

    Route::get('login', LoginController::class)->name('login');

    Route::get('forgot-password', PasswordResetLinkController::class)->name('password.request');

    Route::get('reset-password/{token}', NewPasswordController::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::get('confirm-password', ConfirmablePasswordController::class)->name('password.confirm');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});
