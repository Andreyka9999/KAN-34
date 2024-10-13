<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route for user registration. It accepts a POST request and calls the 'register' method of the AuthController.
Route::post('/register', [AuthController::class, 'register']);
// Route for user login. It accepts a POST request and calls the 'login' method of the AuthController.
Route::post('/login', [AuthController::class, 'login']);
// Route for user logout. It accepts a POST request and calls the 'logout' method of the AuthController.
// This route is protected by the 'auth:sanctum' middleware, ensuring that only authenticated users can access it.
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route to get the authenticated user's information. It accepts a GET request.
// The 'auth:sanctum' middleware ensures that only users with a valid token can access this route.
Route::middleware('auth:sanctum')->get('/user', function (Request $request)
{
    // Returns the authenticated user's data.
    return $request->user();
});
