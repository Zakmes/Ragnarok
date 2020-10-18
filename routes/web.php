<?php

use App\Domains\Announcements\Http\Controllers\OverviewController;
use App\Domains\Api\Http\Controllers\Web\UserTokensController;
use App\Domains\Users\Http\Controllers\RecoveryTokensController;
use App\Domains\Users\Http\Controllers\Settings\UpdateInformationController;
use App\Domains\Users\Http\Controllers\Settings\UpdatePasswordController;
use App\Domains\Users\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\HomeController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'App\Http\Controllers'], static function () {
    Auth::routes();
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Account settings route
Route::view('/account-information', 'users.settings.information')->middleware(['auth', '2fa'])->name('account.information');
Route::get('/account-security', [UpdatePasswordController::class, 'index'])->name('account.security');
Route::patch('/account-information', UpdateInformationController::class)->name('account.information.patch');
Route::patch('/account-security', UpdatePasswordController::class)->name('account.security.patch');

if (config('spoon.modules.api-tokens')) {
    Route::get('/api-tokens', [UserTokensController::class, 'show'])->name('account.tokens');
    Route::post('/api-tokens', [UserTokensController::class, 'store'])->name('account.tokens');
    Route::get('/api-token/{accessToken}/revoke', [UserTokensController::class, 'revoke'])->name('account.tokens.revoke');
}

if (config('spoon.modules.announcements')) {
    Route::get('/announcements', [OverviewController::class, 'index'])->name('announcements.index');
    Route::get('/announcement/{announcement}/mark-as-read', [OverviewController::class, 'markAsRead'])->name('announcements.mark');
}

if (config('google2fa.enabled')) {
    Route::get('/account-2fa/regenerate-tokens', [RecoveryTokensController::class, 'regenerate'])->name('2faTokens.regenerate');
    Route::post('/account-2fa-generate', [TwoFactorAuthController::class, 'generate2faSecret'])->name('generate2faSecret');
    Route::post('/account-2fa', [TwoFactorAuthController::class, 'enable2fa'])->name('enable2fa');
    Route::delete('/account-2fa-deactivate', [TwoFactorAuthController::class, 'disable2fa'])->name('disable2fa');
    Route::post('/2faVerify', static function () {
        return redirect()->url(RouteServiceProvider::HOME);
    })->name('2faVerify')->middleware('2fa');
}
