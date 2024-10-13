<?php

namespace Tests\Unit;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_user_can_register() 
    {
        // The massive of authentication data
        $data = [
            'name' => 'User',
            'email' => 'test@example.com',
            'password' => 'passwordd',
            'password_confirmation' => 'passwordd',
            
        ];

        // Send data to register method in AuthController
        $response = $this->postJson('api/register', $data);
    
        // Chekc if the received status is equal to 201 (Created)
        $response->assertStatus(201);

        // Check that the JSON response contains the 'access_token' and 'token_type' keys. This data is returned when registration is successful.
        $response->assertJsonStructure(['access_token', 'token_type']);

        // Make sure that the user has been added to the 'users' table with the specified email.
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        

    
    }

    public function test_user_can_login()
    {
        // Create a new user with a name, email and password.
        $user = User::create([
            'name' => 'User',
            'email' => 'test@example',
            // Password hashing to store the password in encrypted form for better protection.  
            'password' =>Hash::make('passwordd'),

        ]);

        // Data for login
        $data = [
            'email' =>$user->email,
            'password' => 'passwordd',
        ];


        // Create user request
        $request = new Request($data);

        // Create AuthController instance.
        $controller = new AuthController();

        // Call Login method in AutthController and spass the request object to it.
        // The login method should return a response containing data such as token.

        $response = $controller->login($request);

        // Check if the status is 200 (OK)
        // If the status is not 200 that means ERROR
        $this->assertEquals(200, $response->getStatusCode());

        // Get the data from the answer as an array for futher checking.
        $responseData = $response->getData(true);

        // Check if the response has key "access_token".
        $this->assertArrayHasKey('access_token', $responseData);
    }

        public function test_user_can_logout() {

            // Create a user
            $user = User::factory()->create([
                'password' => Hash::make('password'),
            ]);

            // Authenticate a user using Sanctum and create a token

            $token = $user->createToken('auth_token')->plainTextToken;

            // Use a Sanctum to imitate authentication 

            Sanctum::actingAs($user, ['*']);

            // Make POST request for login

            $response = $this->postJson('/api/logout', [], [
                'Authorization' => 'Bearer' .$token,
            ]);

            // Check if the response status ir 200 (OK)

            $response->assertStatus(200);
            // Check if the response contains a successful exit message

            $response->assertJson([
                'message' => 'Logged out successfully',
            ]);

            // Check that all users tokens have been deleted

            $this->assertCount(0, $user->tokens);


        }
    }