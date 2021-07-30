<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('forgot-password', 'AuthController@forgotPassword');
Route::post('reset-password', 'AuthController@resetPassword');

Route::get('logout', 'AuthController@logout');

Route::resource('user', UserController::class)->only([
    'index', 'show', 'destroy', 'update'
]);
Route::get('user/schedule/{user}', 'ScheduleController@getSchedule');  // policy : only authorize for doctor
Route::get('bookings', 'BookingController@getBookings');   // policy : only authorize for doctor and admin (Because doctor's schedual can be seen by doctor or admin)
Route::get('appointments', 'BookingController@getAppointments'); // policy : only authorize for patient and admin doctor cannot see patients personal appointments

Route::resource('schedule', ScheduleController::class)->only([
    'index', 'show', 'destroy', 'update', 'store'
]);
// Update Doctor's weekly Schedule
Route::put('update-doctor-schedule','ScheduleController@updateDoctorSchedule');

Route::resource('booking', BookingController::class)->only([
    'index', 'show', 'store', 'destroy', 'update'
]);
