<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;

/*
| Public routes (catalog)
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/courses', [CourseController::class, 'indexPublic'])->name('courses.indexPublic');

/*
| Auth protected routes (admin / management)
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout / subscription routes that require auth
    Route::get('/checkout', [SubscriptionController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout/process', [SubscriptionController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout/success', [SubscriptionController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel',  [SubscriptionController::class, 'cancel'])->name('checkout.cancel');

    // Courses admin/resource routes (except public show)
    Route::resource('courses', CourseController::class);
    // ->except(['show'])
    // Modules & lessons (nested resources as needed)
    Route::resource('courses.modules', ModuleController::class)->shallow();
    Route::resource('modules.lessons', LessonController::class)->shallow();

    // optional route to delete a media item in lessons
    Route::delete('lessons/{lesson}/media/{mediaId}', [LessonController::class, 'removeAttachment'])
         ->name('lessons.media.destroy');
});
Route::get('/courses/{slug}', [CourseController::class, 'showPublic'])->name('courses.showPublic');

require __DIR__.'/auth.php';
