<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubcriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class,'register']);
Route::post('/login', [UserController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);
});
Route::get('/get-top', [VideoController::class,'getTop']);
Route::get('/get-new', [VideoController::class,'getNew']);
Route::get('/video-detail/{id}', [VideoController::class,'detail']);
Route::post('/video-like/{id}', [VideoController::class,'like']);
Route::post('/video-add-comment', [VideoController::class,'comment']);
Route::get('/video-comment/{id}', [ReviewController::class,'index']);
Route::delete('/video-comment-delete/{id}', [ReviewController::class,'delete']);
Route::post('/video-add-view/', [VideoController::class,'addView']);
Route::get('/video-check-favorite/{userId}-{videoId}', [FavoriteController::class, 'checkFavorite']);
Route::get('/video-favorite-count/{videoId}', [FavoriteController::class, 'count']);
Route::post('/video-add-favorite', [FavoriteController::class, 'addFavorite']);
Route::delete('/video-remove-favorite', [FavoriteController::class, 'removeFavorite']);
Route::post('/get-data', [VideoController::class, 'getJsonData']);
Route::get('/video-recommend/{id}', [VideoController::class, 'recommend']);
Route::post('/video-upload', [VideoController::class, 'create']);
Route::get('/check-subcription/{userId}-{providerId}', [SubcriptionController::class, 'checkSubcription']);
Route::get('/count-subcription/{providerId}', [SubcriptionController::class, 'count']);
Route::post('/add-subcription', [SubcriptionController::class, 'addSubcription']);
Route::delete('/remove-subcription', [SubcriptionController::class, 'removeSubcription']);
Route::get('/get-subscription/{id}', [SubcriptionController::class, 'index']);
Route::get('/get-all-review/{id}', [ReviewController::class, 'getAll']);
Route::get('/count-subcription/{id}', [SubcriptionController::class, 'countInWeek']);
Route::post('/package-create', [PackageController::class, 'create']);
Route::get('/get-all-package', [PackageController::class, 'index']);
Route::post('/update-package/{id}', [PackageController::class, 'update']);
Route::get('/package-info/{id}', [PackageController::class, 'info']);
Route::post('/checkout', [OrderController::class, 'checkout']);
Route::get('/my-video/{id}', [VideoController::class, 'index']);
Route::get('/dashboard', [OrderController::class, 'dashboard']);
Route::get('/all-user', [UserController::class, 'index']);
Route::get('/all-video', [VideoController::class, 'admin']);
Route::post('/video-confirm', [VideoController::class, 'confirmVideo']);
Route::delete('/delete-video/{id}', [VideoController::class, 'delete']);
Route::get('/all-order', [OrderController::class, 'index']);
Route::post('/user/change-avatar/{id}', [UserController::class, 'changeAvatar']);
