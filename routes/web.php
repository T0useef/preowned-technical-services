<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuotationController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/projects', function () {
    return view('projects');
})->name('projects');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/quotation-template', function () {
    return view('quotation-template');
})->name('quotation.template');

Route::get('/salary-slip/preview', [PaymentController::class, 'salarySlipPreview'])->name('salary-slip.preview');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'projects' => \App\Models\Project::orderBy('name')->get(),
            'users' => \App\Models\User::where('role', 'labour')->orderBy('name')->get(),
        ]);
    })->name('dashboard');

    // users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // projects
    Route::get('/dashboard/projects', [ProjectController::class, 'index'])->name('dashboard.projects.index');
    Route::post('/dashboard/projects', [ProjectController::class, 'store'])->name('dashboard.projects.store');
    Route::put('/dashboard/projects/{project}', [ProjectController::class, 'update'])->name('dashboard.projects.update');
    Route::delete('/dashboard/projects/{project}', [ProjectController::class, 'destroy'])->name('dashboard.projects.destroy');

    // dashboard quick forms
    Route::post('/dashboard/working-hours', [WorkingHourController::class, 'storeWorkingHours'])->name('dashboard.working-hours.store');
    Route::post('/dashboard/overtime-hours', [WorkingHourController::class, 'storeOvertimeHours'])->name('dashboard.overtime-hours.store');

    // payments
    Route::get('/dashboard/payments', [PaymentController::class, 'index'])->name('dashboard.payments.index');
    Route::post('/dashboard/payments/advance', [PaymentController::class, 'storeAdvance'])->name('dashboard.payments.advance.store');
    Route::put('/dashboard/payments/advance/{advancePayment}', [PaymentController::class, 'updateAdvance'])->name('dashboard.payments.advance.update');
    Route::delete('/dashboard/payments/advance/{advancePayment}', [PaymentController::class, 'destroyAdvance'])->name('dashboard.payments.advance.destroy');
    Route::get('/dashboard/payments/salaries', [PaymentController::class, 'salaries'])->name('dashboard.payments.salaries');
    Route::post('/dashboard/payments/salaries/summary', [PaymentController::class, 'salariesSummary'])->name('dashboard.payments.salaries.summary');
    Route::post('/dashboard/payments/salaries/generate-slip', [PaymentController::class, 'generateSalarySlip'])->name('dashboard.payments.salaries.generate-slip');

    // quotations
    Route::get('/dashboard/quotations', [QuotationController::class, 'index'])->name('dashboard.quotations.index');
    Route::get('/dashboard/quotations/{quotation}', [QuotationController::class, 'show'])->name('dashboard.quotations.show');
    Route::post('/dashboard/quotations', [QuotationController::class, 'store'])->name('dashboard.quotations.store');
    Route::put('/dashboard/quotations/{quotation}', [QuotationController::class, 'update'])->name('dashboard.quotations.update');
    Route::delete('/dashboard/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('dashboard.quotations.destroy');

});
require __DIR__.'/auth.php';
