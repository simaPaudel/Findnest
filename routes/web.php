<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\RoommatePreferenceController;
use App\Http\Controllers\User\SavedListingController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\RoommatesController;
use App\Http\Controllers\RoommateMatchController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerPropertyController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Owner\OwnerReviewController;
use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\HomeController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

// Email Verification directly in RegistrationController
Route::get('/verify-email/{token}', [RegistrationController::class, 'verifyEmail'])->name('verify.email');

// User Dashboard 
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('user.bookings.cancel');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Roommate Preferences
    Route::get('/roommate-preferences', [RoommatePreferenceController::class, 'edit'])->name('user.roommate-preferences.edit');
    Route::post('/roommate-preferences', [RoommatePreferenceController::class, 'store'])->name('user.roommate-preferences.store');

    // Saved Listings
    Route::get('/saved-listings', [SavedListingController::class, 'index'])->name('user.saved-listings.index');
    Route::delete('/saved-listings/{savedListing}', [SavedListingController::class, 'destroy'])->name('user.saved-listings.destroy');
    
    // Save/Unsave Listings (API endpoints)
    Route::post('/saved-listings/save/{property}', [SavedListingController::class, 'save'])->name('user.saved-listings.save');
    Route::delete('/saved-listings/unsave/{property}', [SavedListingController::class, 'unsave'])->name('user.saved-listings.unsave');
    Route::get('/saved-listings/check/{property}', [SavedListingController::class, 'isSaved'])->name('user.saved-listings.check');
});

// Owner Dashboard 
Route::prefix('owner')->middleware(['auth', 'owner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');

    // Property/Listings Management
    Route::get('/listings', [OwnerPropertyController::class, 'index'])->name('owner.listings.index');
    Route::get('/listings/create', [OwnerPropertyController::class, 'create'])->name('owner.listings.create');
    Route::post('/listings', [OwnerPropertyController::class, 'store'])->name('owner.listings.store');
    Route::get('/listings/{property}/edit', [OwnerPropertyController::class, 'edit'])->name('owner.listings.edit');
    Route::put('/listings/{property}', [OwnerPropertyController::class, 'update'])->name('owner.listings.update');
    Route::delete('/listings/{property}', [OwnerPropertyController::class, 'destroy'])->name('owner.listings.destroy');
    Route::patch('/listings/{property}/toggle', [OwnerPropertyController::class, 'toggleStatus'])->name('owner.listings.toggle');

    // Bookings Management
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('owner.bookings.index');
    Route::post('/bookings/{booking}/accept', [OwnerBookingController::class, 'accept'])->name('owner.bookings.accept');
    Route::post('/bookings/{booking}/reject', [OwnerBookingController::class, 'reject'])->name('owner.bookings.reject');

    // Reviews
    Route::get('/reviews', [OwnerReviewController::class, 'index'])->name('owner.reviews.index');

    // Profile Management
    Route::get('/profile', [OwnerProfileController::class, 'edit'])->name('owner.profile.edit');
    Route::put('/profile', [OwnerProfileController::class, 'update'])->name('owner.profile.update');
});

// Admin Dashboard
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('admin.properties.index');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('admin.properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('admin.properties.reject');
    Route::post('/properties/{property}/verify', [AdminPropertyController::class, 'verify'])->name('admin.properties.verify');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/reviews/{review}/hide', [AdminReviewController::class, 'hide'])->name('admin.reviews.hide');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
});

// Listings Routes
Route::get('/listings', [ListingsController::class, 'index'])->name('listings.index');
Route::get('/listings/{property}', [ListingsController::class, 'show'])->name('listings.show');

// Roommates Routes
Route::get('/roommates', [RoommatesController::class, 'index'])->name('roommates.index');
Route::get('/roommates/profile', [RoommatesController::class, 'profile'])->middleware('auth')->name('roommates.profile');
Route::get('/roommates/matches', [RoommatesController::class, 'matches'])->middleware('auth')->name('roommates.matches');

// Roommate Matching API Route
Route::get('/roommate/matches', [RoommateMatchController::class, 'getMatches'])->middleware('auth')->name('roommate.matches');
