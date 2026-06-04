<?php

use App\Http\Controllers\Api\N8nProductCallbackController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('n8n/product-callback', N8nProductCallbackController::class)->name('api.n8n.product-callback');
// Route::get('n8n/product-callback', N8ProductCallbackController::class)->name('api.n8n.product-callback');
Route::post('/webhook/gemini', [N8nProductCallbackController::class, 'handleCallback']);