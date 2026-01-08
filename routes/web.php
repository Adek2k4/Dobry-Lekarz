<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search', [DoctorController::class, 'search'])->name('search')->middleware('auth');

Route::get('/dashboard', [AdminController::class, 'dashboard'])->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users/{user}/appointments', [AdminController::class, 'getUserAppointments'])->name('admin.user.appointments');
    Route::get('/admin/users/{user}/reviews', [AdminController::class, 'getUserReviews'])->name('admin.user.reviews');
    Route::get('/admin/doctors/{user}/office-hours', [AdminController::class, 'getDoctorOfficeHours'])->name('admin.doctor.office-hours');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/appointments', [DoctorController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/my-appointments', [DoctorController::class, 'myAppointments'])->name('my-appointments');
    Route::patch('/appointments/{appointment}/status', [DoctorController::class, 'updateStatus'])->name('appointments.updateStatus');
    
    Route::get('/reviews/create', [DoctorController::class, 'createReview'])->name('reviews.create');
    Route::get('/reviews/edit', [DoctorController::class, 'editReview'])->name('reviews.edit');
    Route::post('/reviews', [DoctorController::class, 'storeReview'])->name('reviews.store');
    
    Route::get('/tickets/create', [DoctorController::class, 'createTicket'])->name('tickets.create');
    Route::post('/tickets', [DoctorController::class, 'storeTicket'])->name('tickets.store');
});

require __DIR__.'/auth.php';

Route::get('/{slug}', [DoctorController::class, 'show'])->name('doctor.show');
