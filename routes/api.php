<?php

use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\GeoLocationController;
use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\Plan\ProductController;
use App\Http\Controllers\Api\Plan\SubscriptionController;
use App\Http\Controllers\Api\Plan\StripeWebhooksController;
use App\Http\Controllers\Api\ProfileAvailableController;
use App\Http\Controllers\Api\User\ProfileFieldsController;
use App\Http\Controllers\Api\User\RegisterController;
use App\Http\Controllers\Api\User\UniqueEmailController;
use App\Http\Controllers\Api\User\UniqueUsernameController;
use App\Http\Controllers\Api\User\UserPhotoController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\UsersBlockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/health', HealthCheckController::class);

Route::group(['prefix' => 'ads'], function () {
    Route::get('active-last-48-hours', [AdsController::class, 'activeWithinLast48Hours']);
    Route::post('', [AdsController::class, 'store']);
    Route::put('', [AdsController::class, 'update']);
    Route::get('{id}', [AdsController::class, 'showById']);
    Route::get('locale/{locale}', [AdsController::class, 'getByLocale']);
    Route::get('km/{km}', [AdsController::class, 'getByDistance']);
    Route::get('user/{id}', [AdsController::class, 'showByUserId']);
    Route::get('user/active/{id}', [AdsController::class, 'showByUserActiveId']);
    Route::delete('{id}', [AdsController::class, 'destroy']);
});


Route::group(['prefix' => 'user'], function () {
    Route::get('', [UserProfileController::class, 'loggedUser'])->middleware('auth:sanctum');
    Route::post('/unique/username', UniqueUsernameController::class);
    Route::post('/unique/email', UniqueEmailController::class);
    Route::get('/fields', ProfileFieldsController::class);

    Route::group(['prefix' => 'register', 'middleware' => ['auth:sanctum']], function () {
        Route::patch('/step-one', [RegisterController::class, 'register_step_one']);
        Route::patch('/step-two', [RegisterController::class, 'register_step_two']);
        Route::patch('/step-three', [RegisterController::class, 'register_step_three']);
        Route::patch('/step-four', [RegisterController::class, 'register_step_four']);
        Route::post('/step-five', [RegisterController::class, 'register_step_five']);
        Route::patch('/step-six', [RegisterController::class, 'register_step_six']);
        Route::patch('/decrement-step', [RegisterController::class, 'decrementStep']);
    });

    Route::group(['prefix' => 'profiles', 'middleware' => ['auth:sanctum']], function () {
        Route::patch('', [UserProfileController::class, 'update']);

        Route::group(['prefix' => 'photos'], function () {
            Route::post('', [UserPhotoController::class, 'store']);
            Route::delete('{id}', [UserPhotoController::class, 'destroy']);
            Route::patch('/{id}/isprofile', [UserPhotoController::class, 'setPhotoProfile']);
            Route::patch('/{id}/isprivate', [UserPhotoController::class, 'setPrivatePhoto']);
            Route::patch('/{id}/ispublic', [UserPhotoController::class, 'setPublicPhoto']);
        });
    });
});

Route::group(['prefix' => 'profiles', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/news', [ProfileAvailableController::class, 'news']);
    Route::get('/favorites', [ProfileAvailableController::class, 'favorites']);
    Route::get('/favoritedme', [ProfileAvailableController::class, 'favoritedme']);
    Route::get('/closest', [ProfileAvailableController::class, 'closest']);
    Route::get('/visitedme', [ProfileAvailableController::class, 'visitedme']);
    Route::get('/search', [ProfileAvailableController::class, 'search']);
    Route::get('{id}', [ProfileAvailableController::class, 'show']);
});

Route::group(['prefix' => 'favorites', 'middleware' => ['auth:sanctum']], function () {
    Route::post('', [FavoriteController::class, 'store']);
    Route::delete('{id}', [FavoriteController::class, 'destroy']);
});

Route::group(['prefix' => 'geolocation', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/autocomplete', [GeoLocationController::class, 'autocomplete']);
    Route::post('/lookup', [GeoLocationController::class, 'lookup']);
});

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify-api');


Route::group(['prefix' => 'plans'], function () {
    Route::get('', [ProductController::class, 'index']);
});


Route::group(['prefix' => 'subscriptions', 'middleware' => ['auth:sanctum']], function () {
    Route::post('', [SubscriptionController::class, 'store']);
    Route::post('free', [SubscriptionController::class, 'storeFree']);
    Route::post('create-payment-intent', [SubscriptionController::class, 'createPaymentIntent']);
    Route::group(['prefix' => 'customers'], function () {
        Route::get('active', [SubscriptionController::class, 'getActiveByUserLogged']);
    });
});

Route::post('/stripe_webhooks', [StripeWebhooksController::class, 'handle']);

// external
Route::group(['prefix' => 'external', 'middleware' => ['auth:sanctum', 'token.scope:backoffice']], function () {
    Route::group(['prefix' => 'geolocation'], function () {
        Route::post('/autocomplete', [GeoLocationController::class, 'autocomplete']);
        Route::post('/lookup', [GeoLocationController::class, 'lookup']);
    });
    Route::post('/subscription/free/activation', [SubscriptionController::class, 'storeFreeOnActivation']);
});


Route::group(['prefix' => 'chats'], function () {
    Route::get('/', [ChatsController::class, 'list']);
    Route::post('/', [ChatsController::class, 'create']);
    Route::put('/', [ChatsController::class, 'update']);
    Route::get('/messages', [ChatsController::class, 'messages']);
});

Route::group(['prefix' => 'blockeds'], function () {
    Route::get('/', [UsersBlockController::class, 'list']);
    Route::post('/', [UsersBlockController::class, 'create']);
    Route::delete('/{id}', [UsersBlockController::class, 'destroy']);
    Route::get('/is-blocked/{id}', [UsersBlockController::class, 'isBlocked']);
});
