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
use App\Http\Controllers\User\OwnerApplicationController;
use App\Http\Controllers\User\SavedListingController;
use App\Http\Controllers\User\ConversationController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\RoommatesController;
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
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\OwnerApplicationController as AdminOwnerApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\KhaltiPaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AdminReportController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact-us', [PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/help-center', [PageController::class, 'helpCenter'])->name('pages.help-center');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'blocked'])->get('/notifications/{notification}/open', [NotificationController::class, 'open'])
    ->whereNumber('notification')
    ->name('notifications.open');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

// Email Verification directly in RegistrationController
Route::get('/verify-email/{token}', [RegistrationController::class, 'verifyEmail'])->name('verify.email');

// Debug endpoint (remove in production)
Route::get('/debug/verify/{token}', function ($token) {
    try {
        \Illuminate\Support\Facades\Log::info('Debug: Attempting to find user with token', ['token' => substr($token, 0, 10) . '...']);

        $user = \App\Models\User::where('verification_token', $token)->first();

        if ($user) {
            return [
                'found' => true,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'is_verified' => $user->is_verified,
                'current_token' => substr($user->verification_token ?? '', 0, 10),
            ];
        }

        return [
            'found' => false,
            'message' => 'User not found with this token',
            'checked_token' => substr($token, 0, 10) . '...',
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
        ];
    }
});

// Test update endpoint
Route::get('/debug/test-update/{token}', function ($token) {
    try {
        $startTime = microtime(true);
        \Illuminate\Support\Facades\Log::info('Debug: Starting verification update test', ['token' => substr($token, 0, 10) . '...']);

        $user = \App\Models\User::where('verification_token', $token)->first();

        if (!$user) {
            return [
                'error' => 'User not found',
            ];
        }

        $beforeUpdate = microtime(true);

        // Try direct update
        $result = \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->update([
                'is_verified' => 1,
                'verification_token' => null,
                'email_verified_at' => now(),
            ]);

        $afterUpdate = microtime(true);
        $totalTime = $afterUpdate - $startTime;
        $updateTime = $afterUpdate - $beforeUpdate;

        return [
            'success' => true,
            'rows_updated' => $result,
            'user_id' => $user->id,
            'total_time_ms' => ($totalTime * 1000),
            'update_time_ms' => ($updateTime * 1000),
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }
});

// User Dashboard 
Route::prefix('user')->middleware(['auth', 'blocked', 'user'])->group(function () {
    // Redirect old dashboard to listings (new main flow)
    Route::get('/dashboard', function () {
        return redirect()->route('listings.index');
    })->name('user.dashboard');

    // Bookings
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('user.bookings.index');
    Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('user.bookings.show');
    Route::get('/bookings/{booking}/edit', [UserBookingController::class, 'edit'])->name('user.bookings.edit');
    Route::put('/bookings/{booking}', [UserBookingController::class, 'update'])->name('user.bookings.update');
    Route::put('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('user.bookings.cancel');
    Route::get('/bookings/{booking}/bill', [UserBookingController::class, 'bill'])->name('user.bookings.bill');
    Route::get('/bookings/{booking}/invoice/download', [UserBookingController::class, 'downloadInvoice'])->name('user.bookings.download-invoice');
    Route::post('/bookings/{booking}/review', [UserBookingController::class, 'storeReview'])->name('user.bookings.review');
    Route::post('/bookings/{booking}/trust-point', [UserBookingController::class, 'storeTrustPoint'])->name('user.bookings.trust-point');
    Route::post('/bookings/check-availability', [UserBookingController::class, 'checkAvailability'])->name('user.bookings.check-availability');
    Route::get('/bookings/available-dates', [UserBookingController::class, 'getAvailableDates'])->name('user.bookings.available-dates');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Become a Host
    Route::get('/host-application', [OwnerApplicationController::class, 'show'])->name('user.host-application.show');
    Route::post('/host-application', [OwnerApplicationController::class, 'store'])->name('user.host-application.store');

    // Roommate Preferences
    Route::get('/roommate-preferences', [RoommatePreferenceController::class, 'edit'])->name('user.roommate-preferences.edit');
    Route::post('/roommate-preferences', [RoommatePreferenceController::class, 'store'])->name('user.roommate-preferences.store');

    // Saved Listings
    Route::get('/saved-listings', [SavedListingController::class, 'index'])->name('user.saved-listings.index');
    Route::post('/saved-listings/save/{property}', [SavedListingController::class, 'save'])->name('user.saved-listings.save');
    Route::delete('/saved-listings/unsave/{property}', [SavedListingController::class, 'unsave'])->name('user.saved-listings.unsave');
    Route::get('/saved-listings/check/{property}', [SavedListingController::class, 'isSaved'])->name('user.saved-listings.check');
    Route::delete('/saved-listings/{savedListing}', [SavedListingController::class, 'destroy'])->name('user.saved-listings.destroy');

    // Messaging
    Route::get('/messages', [ConversationController::class, 'inbox'])
        ->name('user.messages.index');
    Route::post('/conversations/property/{propertyId}', [ConversationController::class, 'createOrOpenPropertyConversation'])
        ->whereNumber('propertyId')
        ->name('user.conversations.property.create-or-open');
    Route::post('/conversations/roommate/{userId}', [ConversationController::class, 'createOrOpenRoommateConversation'])
        ->whereNumber('userId')
        ->name('user.conversations.roommate.create-or-open');
    Route::get('/conversations/unread-count', [ConversationController::class, 'getUnreadCount'])
        ->name('user.conversations.unread-count');
    Route::get('/conversations/poll', [ConversationController::class, 'pollNewMessages'])
        ->name('user.conversations.poll');
    Route::get('/conversations/{conversationId}', [ConversationController::class, 'showConversation'])
        ->whereNumber('conversationId')
        ->name('user.conversations.show');
    Route::post('/conversations/{conversationId}/messages', [ConversationController::class, 'sendMessage'])
        ->whereNumber('conversationId')
        ->name('user.conversations.messages.send');
});

// Owner Dashboard 
Route::prefix('owner')->middleware(['auth', 'blocked', 'owner'])->group(function () {
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

    // Image Management
    Route::delete('/listings/{property}/images/{image}', [OwnerPropertyController::class, 'deleteImage'])->name('owner.listings.delete-image');
    Route::patch('/listings/{property}/images/{image}/primary', [OwnerPropertyController::class, 'setPrimaryImage'])->name('owner.listings.set-primary-image');

    // Room Management
    Route::get('/listings/{property}/rooms', [OwnerPropertyController::class, 'roomsIndex'])->name('owner.rooms.index');
    Route::get('/listings/{property}/rooms/create', [OwnerPropertyController::class, 'createRoom'])->name('owner.rooms.create');
    Route::post('/listings/{property}/rooms', [OwnerPropertyController::class, 'storeRoom'])->name('owner.rooms.store');
    Route::get('/listings/{property}/rooms/{room}/edit', [OwnerPropertyController::class, 'editRoom'])->name('owner.rooms.edit');
    Route::put('/listings/{property}/rooms/{room}', [OwnerPropertyController::class, 'updateRoom'])->name('owner.rooms.update');
    Route::delete('/listings/{property}/rooms/{room}', [OwnerPropertyController::class, 'destroyRoom'])->name('owner.rooms.destroy');

    // Room Image Management
    Route::delete('/listings/{property}/rooms/{room}/images/{image}', [OwnerPropertyController::class, 'deleteRoomImage'])->name('owner.rooms.delete-image');
    Route::patch('/listings/{property}/rooms/{room}/images/{image}/primary', [OwnerPropertyController::class, 'setRoomPrimaryImage'])->name('owner.rooms.set-primary-image');

    // Bookings Management
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('owner.bookings.index');
    Route::post('/bookings/{booking}/accept', [OwnerBookingController::class, 'accept'])->name('owner.bookings.accept');
    Route::post('/bookings/{booking}/reject', [OwnerBookingController::class, 'reject'])->name('owner.bookings.reject');
    Route::post('/bookings/{booking}/trust-point', [OwnerBookingController::class, 'storeTrustPoint'])->name('owner.bookings.trust-point');

    // Reviews
    Route::get('/reviews', [OwnerReviewController::class, 'index'])->name('owner.reviews.index');

    // Messaging
    Route::get('/messages', [ConversationController::class, 'inbox'])->name('owner.messages.index');
    Route::get('/conversations/unread-count', [ConversationController::class, 'getUnreadCount'])
        ->name('owner.conversations.unread-count');
    Route::get('/conversations/poll', [ConversationController::class, 'pollNewMessages'])
        ->name('owner.conversations.poll');
    Route::get('/conversations/{conversationId}', [ConversationController::class, 'showConversation'])
        ->whereNumber('conversationId')
        ->name('owner.conversations.show');
    Route::post('/conversations/{conversationId}/messages', [ConversationController::class, 'sendMessage'])
        ->whereNumber('conversationId')
        ->name('owner.conversations.messages.send');

    // Profile Management
    Route::get('/profile', [OwnerProfileController::class, 'edit'])->name('owner.profile.edit');
    Route::put('/profile', [OwnerProfileController::class, 'update'])->name('owner.profile.update');
    
    // DEBUG: Test endpoint for form submission debugging
    Route::post('/listings-debug', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Log::info('DEBUG: Raw form data received', [
            'method' => $request->method(),
            'all_input' => $request->all(),
            'has_files' => $request->hasAnyFile(),
            'file_keys' => $request->files->keys(),
            'content_type' => $request->header('Content-Type'),
            'user_id' => auth()->id(),
        ]);
        
        return response()->json(['status' => 'received', 'timestamp' => now()]);
    })->name('owner.listings.debug');
});


// Admin Dashboard
Route::prefix('admin')->middleware(['auth', 'blocked', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/properties', [AdminPropertyController::class, 'index'])->name('admin.properties.index');
    Route::post('/properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('admin.properties.approve');
    Route::post('/properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('admin.properties.reject');
    Route::post('/properties/{property}/verify', [AdminPropertyController::class, 'verify'])->name('admin.properties.verify');
    Route::delete('/properties/{property}', [AdminPropertyController::class, 'destroy'])->name('admin.properties.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->whereNumber('user')->name('admin.users.show');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->whereNumber('user')->name('admin.users.toggle-status');
    Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->whereNumber('user')->name('admin.users.update-role');

    Route::get('/owner-applications', [AdminOwnerApplicationController::class, 'index'])->name('admin.owner-applications.index');
    Route::get('/owner-applications/{ownerApplication}', [AdminOwnerApplicationController::class, 'show'])->name('admin.owner-applications.show');
    Route::post('/owner-applications/{ownerApplication}/approve', [AdminOwnerApplicationController::class, 'approve'])->name('admin.owner-applications.approve');
    Route::post('/owner-applications/{ownerApplication}/reject', [AdminOwnerApplicationController::class, 'reject'])->name('admin.owner-applications.reject');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/reviews/{review}/hide', [AdminReviewController::class, 'hide'])->name('admin.reviews.hide');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->whereNumber('booking')->name('admin.bookings.show');
    Route::post('/bookings/{booking}/release', [AdminBookingController::class, 'release'])->name('admin.bookings.release');
    Route::post('/payments/{payment}/mark-paid', [AdminBookingController::class, 'markPayoutPaid'])->whereNumber('payment')->name('admin.payments.mark-paid');

    // Profile Management
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    // Reports Management
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('admin.reports.show');
    Route::post('/reports/{report}/review', [AdminReportController::class, 'review'])->name('admin.reports.review');
    Route::get('/reports/{report}/resolve', [AdminReportController::class, 'editResolution'])->name('admin.reports.edit-resolution');
    Route::put('/reports/{report}/resolve', [AdminReportController::class, 'updateResolution'])->name('admin.reports.update-resolution');
    Route::post('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('admin.reports.dismiss');
    Route::get('/reports/statistics', [AdminReportController::class, 'statistics'])->name('admin.reports.statistics');
});

// Listings Routes
Route::get('/listings', [ListingsController::class, 'index'])->name('listings.index');
Route::get('/listings/{property}', [ListingsController::class, 'show'])->name('listings.show');

// Booking Request Route (Auth Required)
Route::get('/listings/{property}/request-booking', [UserBookingController::class, 'request'])
    ->middleware(['auth', 'user'])
    ->name('listings.request-booking');

Route::post('/bookings/create', [UserBookingController::class, 'create'])
    ->middleware(['auth', 'user'])
    ->name('user.bookings.create');

// Report Routes (Auth Required)
Route::middleware(['auth', 'blocked'])->prefix('reports')->group(function () {
    Route::get('/my-reports', [ReportController::class, 'myReports'])->name('report.my-reports');
    Route::get('/{report}', [ReportController::class, 'show'])->name('report.show');
    Route::get('/create/{reportableType}/{reportableId}', [ReportController::class, 'create'])->name('report.create');
    Route::post('/store', [ReportController::class, 'store'])->name('report.store');
});

// Roommates Routes
Route::get('/roommates', [RoommatesController::class, 'index'])->name('roommates.index');
Route::get('/roommates/profile', [RoommatesController::class, 'profile'])->middleware(['auth', 'blocked'])->name('roommates.profile');
Route::get('/roommates/matches', [RoommatesController::class, 'matches'])->middleware(['auth', 'blocked'])->name('roommates.matches');

// Khalti Payment Routes
Route::middleware(['auth', 'user'])->prefix('payment/khalti')->group(function () {
    Route::get('/initiate/{bookingId}', [KhaltiPaymentController::class, 'initiate'])->name('payment.khalti.initiate');
    Route::get('/success', [KhaltiPaymentController::class, 'success'])->name('payment.khalti.success');
    Route::get('/failure', [KhaltiPaymentController::class, 'failure'])->name('payment.khalti.failure');
});
