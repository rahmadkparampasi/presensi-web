<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AbsenSispController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BagController;
use App\Http\Controllers\LapApiController;
use App\Http\Controllers\SetpdController;
use App\Http\Controllers\SispController;
use App\Http\Controllers\SurveisApiController;
use App\Http\Controllers\UserController;
use App\Http\Resources\MskResource;
use App\Http\Resources\SetkrjResource;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(BagController::class)->group(function(){
    Route::get('/satker','getDataSatker');
    Route::get('/ppk/{satker?}','getDataPpk');
    // Route::get('/absen/getYear','getYear')->middleware('auth:sanctum');
    // Route::get('/absen/getMonth/{year?}','getMonthByYear')->middleware('auth:sanctum');
    // Route::get('/absen/month/{month?}/{year?}', 'showMonth')->middleware('auth:sanctum');
    // Route::get('/absen/last', 'showLast')->middleware('auth:sanctum');
    // Route::get('/absen/getKet', 'getKetToday')->middleware('auth:sanctum');
    // Route::get('/absen/rekap/{month?}/{year?}', 'showRekap')->middleware('auth:sanctum');
    // Route::get('/absen/calendar/{month?}/{year?}', 'showCalendar')->middleware('auth:sanctum');
    // Route::get('/absen/detail/{id}', 'showDetail')->middleware('auth:sanctum');
    // Route::post('/absen/insert', 'insertData');
});

Route::controller(AbsenSispController::class)->group(function(){
    Route::get('/absen/getYear','getYear')->middleware('auth:sanctum');
    Route::get('/absen/getMonth/{year?}','getMonthByYear')->middleware('auth:sanctum');
    Route::get('/absen/month/{month?}/{year?}', 'showMonth')->middleware('auth:sanctum');
    Route::get('/absen/last', 'showLast')->middleware('auth:sanctum');
    Route::get('/absen/detail/{id}', 'showDetail')->middleware('auth:sanctum');
    Route::get('/absen/detailDate', 'showDetailByDate')->middleware('auth:sanctum');
    // Route::get('/absen/getKet', 'getKetToday')->middleware('auth:sanctum');
    Route::get('/absen/rekap/{month?}/{year?}', 'showRekap')->middleware('auth:sanctum');
    // Route::get('/absen/calendar/{month?}/{year?}', 'showCalendar')->middleware('auth:sanctum');
    Route::post('/absen/insert', 'insertData')->middleware('auth:sanctum');
    Route::post('/absen/update', 'updateData')->middleware('auth:sanctum');

    Route::get('/sisp/detail', 'sispDetail')->middleware('auth:sanctum');
    Route::post('/sisp/updatePic', 'updateDataPic')->middleware('auth:sanctum');
    Route::post('/sisp/update', 'updateDataGuru')->middleware('auth:sanctum');
});

Route::controller(LapApiController::class)->group(function(){
    Route::get('/lap/profil/','detailProfil')->middleware('auth:sanctum');
    Route::get('/lap/year','getYear')->middleware('auth:sanctum');
    Route::get('/lap/month','getMonth')->middleware('auth:sanctum');
    Route::post('/lap/insert', 'insertData')->middleware('auth:sanctum');
    Route::post('/lap/update', 'updateData')->middleware('auth:sanctum');
});

Route::controller(SurveisApiController::class)->group(function(){
    Route::get('/survei/profil/','profil')->middleware('auth:sanctum');
    Route::get('/survei/profilA/{id}','profilA')->middleware('auth:sanctum');
    // Route::get('/lap/year','getYear')->middleware('auth:sanctum');
    // Route::get('/lap/month','getMonth')->middleware('auth:sanctum');
    // Route::post('/lap/insert', 'insertData')->middleware('auth:sanctum');
    // Route::post('/lap/update', 'updateData')->middleware('auth:sanctum');
});

Route::get('/setpd', [SetpdController::class, 'getAPI'])->middleware('auth:sanctum');

Route::post('/user/updatePwdA', [UserController::class, 'updateDataPWDAPI'])->middleware('auth:sanctum');

Route::post('/registerM', [SispController::class, 'insertDataGuruM']);

Route::post('/masuk', [AuthenticationController::class, 'login']);