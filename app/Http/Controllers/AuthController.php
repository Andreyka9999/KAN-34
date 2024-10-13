<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) 
    {


        // Input data validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:9|',

        ]);

        // If the validation was failed then return error with 422 status
        if ($validator->fails() ) 
        {
            return response()->json($validator->errors(), 422);
        }

        // New user registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), //Password hashing before save it
        ]);


        // API tokens generation for user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return successful response with token and with status 201 "Created"
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), 
        [
            'email' => 'required|string|email',
            'password' => 'required|string|',
        ]);

        // If validaton fails then return error status 422 "Unprocessable Entity"

        if($validator->fails()) 
        {
            return response()->json($validator->errors(), 422);
        }

        // Find user using email

        $user = User::where('email', $request->input('email'))->first();

        // Check if the password was correct, and if not, then return error with status 401 "Unauthorized"

        if(!$user || !Hash::check($request->password, $user->password)) 
        {
            return response()->json([
                'message'=> 'Incorrect registration data'], 401);
        }

        // Create token for user

        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token in response, with status 200 - OK

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request) 
    {
        // Check that the user has currently active token 
        if ($request->user())
        {
            $request->user()->tokens()->delete();

            // Return successful message
            return response()->json([
                'message' => 'Logged out successfully',
            ], 200);

        }

        // If the token wasn`t found return error
        return response()->json([
            'message' => 'No active access token found.',
        ], 400);

}
}