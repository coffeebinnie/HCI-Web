<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrototypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::get('/login', [AuthController::class, 'create']);
    Route::post('/login', [AuthController::class, 'store'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.attempt');
    Route::get('/forgot-password', [PrototypeController::class, 'forgotPassword'])->name('password.request');
    Route::get('/reset-sent', [PrototypeController::class, 'resetSent'])->name('password.sent');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('transactions', TransactionController::class)
        ->except(['show', 'create'])
        ->names(['index' => 'transactions']);

    Route::resource('budgets', BudgetController::class)
        ->except(['show', 'create', 'edit'])
        ->names(['index' => 'budgets']);

    Route::get('/savings', [SavingsGoalController::class, 'index'])->name('savings');
    Route::get('/savings/contribution', [SavingsGoalController::class, 'contribution'])->name('savings.contribution');
    Route::post('/savings-goals', [SavingsGoalController::class, 'store'])->name('savings-goals.store');
    Route::put('/savings-goals/{savingsGoal}', [SavingsGoalController::class, 'update'])->name('savings-goals.update');
    Route::delete('/savings-goals/{savingsGoal}', [SavingsGoalController::class, 'destroy'])->name('savings-goals.destroy');

    Route::post('/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    Route::put('/contributions/{contribution}', [ContributionController::class, 'update'])->name('contributions.update');
    Route::delete('/contributions/{contribution}', [ContributionController::class, 'destroy'])->name('contributions.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    Route::get('/settings', [PrototypeController::class, 'settings'])->name('settings');
    Route::get('/profile', [PrototypeController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [PrototypeController::class, 'editProfile'])->name('profile.edit');
    Route::get('/settings/password', [PrototypeController::class, 'password'])->name('settings.password');
    Route::post('/settings/password', fn () => redirect()->route('changes.saved'))->name('settings.password.update');
    Route::get('/settings/privacy', [PrototypeController::class, 'privacy'])->name('settings.privacy');
    Route::get('/changes-saved', [PrototypeController::class, 'saved'])->name('changes.saved');

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
