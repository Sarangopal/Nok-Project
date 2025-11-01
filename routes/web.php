<?php

namespace App\Http\Controllers;  // Must be first line

use Illuminate\Support\Facades\Route;
use Livewire\Features\SupportFileUploads\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;     
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MembershipCardController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\MemberPasswordResetController;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;

Route::get('/', function () {
    return view('index');
});

// Public membership verification (rate limited to prevent abuse)
Route::get('/verify-membership', [VerificationController::class, 'showForm'])->name('verify-member.form');
Route::post('/verify-membership', [VerificationController::class, 'verify'])
    ->middleware('throttle:10,1') // 10 requests per minute
    ->name('verify-member.post');

// Friendly aliases for legacy references
Route::get('/verify', [VerificationController::class, 'showForm'])->name('verify');
Route::get('/public/verify', [VerificationController::class, 'showForm'])->name('public.verify');

// Livewire File Upload Routes (CRITICAL for FileUpload component)
// These routes should be auto-registered by Livewire but adding them explicitly
Route::post('/livewire/upload-file', [FileUploadController::class, 'handle'])
    ->middleware(['web'])
    ->name('livewire.upload-file');
    
Route::get('/livewire/preview-file/{filename}', [\Livewire\Features\SupportFileUploads\FilePreviewController::class, 'handle'])
    ->middleware(['web'])
    ->name('livewire.preview-file');



// The form element from the diff would need to be added to a Blade template file, 
// not the routes file. This routes file already contains the named route 'verify-member.submit'

// Show Patrons message
Route::get('/patrons_message', function () {
    return view('Patrons_message');
});

// Show presidents message page
Route::get('/presidents_message', function () {
	return view('Presidents_message');
});

//show treasurer message page

// Show presidents message page
Route::get('/treasurer_message', function () {
	return view('treasurer_message');
});



// Show arravm
Route::get('/aaravam', function () {
    return view('aaravam');
});

// Show about page
Route::get('/about', function () {
    return view('about');
});

// Show brand mark page
Route::get('/our_logo', function () {
    return view('Brand_mark');
});


// Contact page (GET)
Route::get('/contact', [ContactController::class, 'show'])->name('contact');

// Contact form submit (POST)
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Show core values page
Route::get('/core_values', function () {
    return view('core_values');
});

// Events routes (dynamic from database)
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Show executive committee 25_26 page
Route::get('/executive_committee_25_26', function () {
    return view('executive_committee_25_26');
});

// Show executive committee page
Route::get('/executive_committee', function () {
	return view('executive_committee');
});

// Show founding of nok page
Route::get('/founding_of_nok', function () {
	return view('founding_of_nok');
});

// Show gallery page (dynamic)
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');


// Show Secretarys message page
Route::get('/secretarys_message', function () {
	return view('Secretarys_message');
});


// Legacy Argon registration view removed from admin; keep public if needed
Route::get('/registration', function () {
    return view('registeration');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Handle form submission
Route::post('/registration-submit', [RegistrationController::class, 'submit'])->name('registration.submit');

// Check for duplicate email (AJAX with rate limiting)
Route::post('/check-email', [RegistrationController::class, 'checkEmail'])
    ->middleware('throttle:60,1')
    ->name('registration.checkEmail');

// Check for duplicate phone (AJAX with rate limiting)
Route::post('/check-phone', [RegistrationController::class, 'checkPhone'])
    ->middleware('throttle:60,1')
    ->name('registration.checkPhone');

// Check for duplicate registrations (AJAX - other fields)
Route::post('/check-duplicate', [RegistrationController::class, 'checkDuplicate'])
    ->middleware('throttle:60,1')
    ->name('registration.checkDuplicate');

// Route::get('/membership-card/download/{registration}', [MembershipCardController::class, 'download'])
//     ->name('membership.card.download');
Route::get('/membership-card/download/{record}', [MembershipCardController::class, 'download'])
    ->name('membership.card.download');

// Member panel is handled by Filament (MemberPanelProvider)
// Access at: /member/panel

// Member Password Reset Routes
Route::get('/member/password/reset', [MemberPasswordResetController::class, 'showLinkRequestForm'])
    ->name('member.password.request');
Route::post('/member/password/email', [MemberPasswordResetController::class, 'sendResetLinkEmail'])
    ->name('member.password.email');
Route::get('/member/password/reset/{token}', [MemberPasswordResetController::class, 'showResetForm'])
    ->name('member.password.reset');
Route::post('/member/password/reset', [MemberPasswordResetController::class, 'reset'])
    ->name('member.password.update');

// Renewal request route (used by Filament member panel widget)
Route::middleware('auth:members')->group(function () {
    Route::post('/member/renewal-request', function (Request $request) {
        // Validate the payment proof upload
        $request->validate([
            'payment_proof' => 'required|image|max:10240', // max 10MB
        ], [
            'payment_proof.required' => 'Please upload a payment proof image.',
            'payment_proof.image' => 'The payment proof must be an image file.',
            'payment_proof.max' => 'The payment proof image must not exceed 10MB.',
        ]);

        $member = auth('members')->user();
        
        // Store the payment proof image
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('renewals/payment-proofs', 'public');
            $member->renewal_payment_proof = $paymentProofPath;
        }
        
        $member->renewal_requested_at = now();
        $member->renewal_status = 'pending';
        $member->save();
        
        return redirect()->back()->with('status', 'Renewal request submitted successfully! Awaiting admin approval.');
    })->name('member.renewal.request');
});

// Route::get('/home', function () {return redirect('/dashboard');})->middleware('auth');
// 	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
// 	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
// 	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
// 	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
// 	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
// 	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
// 	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
// 	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
// 	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// 	//user management routes
// 	Route::get('/user-management', [RegistrationController::class, 'showMembers'])->name('members.index');
// 	Route::get('/user-management/{member}/edit', [RegistrationController::class, 'edit'])->name('members.edit');
// 	Route::delete('/user-management/{member}', [RegistrationController::class, 'destroy'])->name('members.destroy');

// 	Route::get('/enquires', [ContactController::class, 'show'])->name('enquires.show');

// 	Route::get('/members/{id}/edit', [RegistrationController::class, 'edit'])->name('members.edit');
// 	Route::put('/members/{id}', [RegistrationController::class, 'update'])->name('members.update');

// 	// Route::get('/membership-renewals', [RegistrationController::class, 'show'])->name('membership-renewals.show');

// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
// 	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
// 	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
// 	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
// 	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
// 	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
// 	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
// 	Route::get('/{page}', [PageController::class, 'index'])->name('page');



// 	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// });