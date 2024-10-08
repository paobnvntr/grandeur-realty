<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ListWithUsController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactUsController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::controller(LandingPageController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('all-properties', 'allProperties')->name('allProperties');
    Route::get('hot-properties/{city}', 'hotProperties')->name('hotProperties');
    Route::get('property-details/{id}', 'propertyDetails')->name('propertyDetails');

    Route::get('list-with-us', 'listWithUsForm')->name('listWithUsForm');
    Route::post('validateListWithUsForm', 'validateListWithUsForm')->name('validateListWithUsForm');
    Route::post('saveListWithUs', 'saveListWithUs')->name('saveListWithUs');

    Route::get('contact-us', 'contactUsForm')->name('contactUsForm');
    Route::post('validateContactUsForm', 'validateContactUsForm')->name('validateContactUsForm');
    Route::post('saveContactUs', 'saveContactUs')->name('saveContactUs');

    Route::get('privacy-policy', 'privacyPolicy')->name('privacyPolicy');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('loginAction', 'loginAction')->name('loginAction');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');

    Route::post('forgot-password', 'forgotPassword')->name('forgotPassword'); 
    Route::get('change-password/{token}/{username}', 'changePasswordForm')->name('changePasswordForm');
    Route::post('change-password', 'changePassword')->name('changePassword');
});

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
    });

    Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
        Route::get('notifications', 'notifications')->name('notifications');
        Route::post('mark-as-read/{id}', 'markAsRead')->name('notifications.markAsRead');
        Route::post('mark-all-as-read', 'markAllAsRead')->name('notifications.markAllAsRead');
    });

    Route::controller(ListWithUsController::class)->prefix('listWithUs')->group(function () {
        Route::get('list-with-us', 'listWithUs')->name('listWithUs');
        Route::post('approveList/{id}', 'approveList')->name('listWithUs.approveList');
        Route::post('disapproveList/{id}', 'disapproveList')->name('listWithUs.disapproveList');
    });

    Route::controller(PropertyController::class)->prefix('properties')->group(function () {
        Route::get('properties', 'properties')->name('properties.List');
        Route::get('sold-properties', 'soldProperties')->name('properties.soldProperties');
        Route::post('markAsSold/{id}', 'markAsSold')->name('properties.markAsSold');
        Route::post('markAsAvailable/{id}', 'markAsAvailable')->name('properties.markAsAvailable');
        Route::post('delete/{id}', 'delete')->name('properties.delete');
    });

    Route::controller(ContactUsController::class)->prefix('contactUs')->group(function () {
        Route::get('contact-us', 'contactUs')->name('contactUs');
        Route::delete('deleteContactUs/{id}', 'deleteContactUs')->name('contactUs.deleteContactUs');
    });

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('users', 'users')->name('users');
        Route::get('add-user', 'addUser')->name('users.addUser');
        Route::post('validateAddUserForm', 'validateAddUserForm')->name('users.validateAddUserForm');
        Route::post('saveUser', 'saveUser')->name('users.saveUser');

        Route::get('edit-user/{id}', 'editUser')->name('users.editUser');
        Route::post('validateEditUserForm/{id}', 'validateEditUserForm')->name('users.validateEditUserForm');
        Route::post('updateUser/{id}', 'updateUser')->name('users.updateUser');

        Route::delete('deleteUser/{id}', 'deleteUser')->name('users.deleteUser');
    });

    Route::controller(LogController::class)->prefix('logs')->group(function () {
        Route::get('logs', 'logs')->name('logs');
    });

    Route::controller(ProfileController::class)->prefix('profile')->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('validateProfileForm/{id}', 'validateProfileForm')->name('profile.validateProfileForm');
        Route::post('updateProfile/{id}', 'updateProfile')->name('profile.updateProfile');
    });
});

