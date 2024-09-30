<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        try {
            // Create a new user
            $user = new User();
            $user->full_name = $req->fullName;
            $user->user_name = $req->userName;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->birth_date = $req->birthDate;

            // Save the user to the database
            $user->save();

            // Automatically log the user in and generate a token
            $token = Auth::attempt([
                'user_name' => $req->userName,
                'password' => $req->password,
            ]);

            if (!$token) {
                return ApiResponse::error(401, 'Unauthorized');
            }

            // Assign token to user and send response
            $user->token = $token;
            $user->save();

            return ApiResponse::success($user, 200, 'Registration successful');
        } catch (Throwable $exception) {
            // Handle any errors
            return ApiResponse::error(500, $exception->getMessage());
        }
    }
    public function login(Request $req)
    {
        try {
            // Attempt to log the user in using the provided credentials
            $credentials = [
                'user_name' => $req->userName,
                'password' => $req->password,
            ];

            if ($token = Auth::attempt($credentials)) {
                // Get the authenticated user
                $user = Auth::user();
                $user->token = $token;

                // Save the user with the token (optional if needed)
                $user->save();

                // Respond with success
                return ApiResponse::success($user, 200, 'Login successful');
            } else {
                // Invalid credentials
                return ApiResponse::error(401, 'Invalid username or password');
            }
        } catch (Throwable $e) {
            // Handle any unexpected errors
            return ApiResponse::error(500, $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            Auth::logout();  // Log the user out
            return ApiResponse::success(null, 200, 'Logout successful');
        } catch (Throwable $e) {
            return ApiResponse::error(500, $e->getMessage());
        }
    }
}
