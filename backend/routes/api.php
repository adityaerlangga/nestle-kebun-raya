<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemperatureSensorController;
use App\Http\Controllers\SoilMoistureSensorController;
use App\Http\Controllers\LightSensorController;
use App\Http\Controllers\TurbiditySensorController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Temperature Sensor routes
Route::get('/temperature-sensors', [TemperatureSensorController::class, 'index']);
Route::get('/temperature-sensors/{sensorName}', [TemperatureSensorController::class, 'show']);
Route::post('/temperature-sensors', [TemperatureSensorController::class, 'store']);
Route::get('/temperature-sensors-names', [TemperatureSensorController::class, 'getSensorNames']);

// Soil Moisture Sensor routes
Route::get('/soil-moisture-sensors', [SoilMoistureSensorController::class, 'index']);
Route::get('/soil-moisture-sensors/{sensorName}', [SoilMoistureSensorController::class, 'show']);
Route::post('/soil-moisture-sensors', [SoilMoistureSensorController::class, 'store']);
Route::get('/soil-moisture-sensors-names', [SoilMoistureSensorController::class, 'getSensorNames']);

// Light Sensor routes
Route::get('/light-sensors', [LightSensorController::class, 'index']);
Route::get('/light-sensors/{sensorName}', [LightSensorController::class, 'show']);
Route::post('/light-sensors', [LightSensorController::class, 'store']);
Route::get('/light-sensors-names', [LightSensorController::class, 'getSensorNames']);

// Turbidity Sensor routes
Route::get('/turbidity-sensors', [TurbiditySensorController::class, 'index']);
Route::get('/turbidity-sensors/{sensorName}', [TurbiditySensorController::class, 'show']);
Route::post('/turbidity-sensors', [TurbiditySensorController::class, 'store']);
Route::get('/turbidity-sensors-names', [TurbiditySensorController::class, 'getSensorNames']); 