<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     * Issue Sanctum Personal Access Token
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }

        try {
            if (!Auth::attempt($validated)) {
                return ApiResponse::unauthorized('Invalid email or password');
            }

            $user = Auth::user();
            $token = $user->createToken('expo-app', $user->getSanctumAbilities());

            return ApiResponse::success([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'org_id' => $user->org_id,
                ],
                'token' => $token->plainTextToken,
                'abilities' => $user->getSanctumAbilities(),
            ], 'Logged in successfully');

        } catch (\Exception $e) {
            \Log::error('Login error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Login failed', $e);
        }
    }

    /**
     * POST /api/v1/auth/logout
     * Revoke current Sanctum token
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponse::success(null, 'Logged out successfully');

        } catch (\Exception $e) {
            \Log::error('Logout error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Logout failed', $e);
        }
    }

    /**
     * GET /api/v1/auth/user
     * Get authenticated user + abilities
     */
    public function user(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            return ApiResponse::success([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'org_id' => $user->org_id,
                ],
                'abilities' => $user->getSanctumAbilities(),
            ], 'User retrieved successfully');

        } catch (\Exception $e) {
            \Log::error('Auth user error', ['error' => $e->getMessage()]);
            return ApiResponse::serverError('Failed to retrieve user', $e);
        }
    }

    /**
     * POST /api/v1/auth/register
     * Register parent account
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'org_id' => 1, // Default to first org
                'role' => 'parent',
            ]);

            $user->assignRole('parent');

            $token = $user->createToken('expo-app', $user->getSanctumAbilities());

            return ApiResponse::success([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'org_id' => $user->org_id,
                ],
                'token' => $token->plainTextToken,
            ], 'User registered successfully', 201);

        } catch (\Exception $e) {
            \Log::error('Registration error', ['email' => $validated['email'], 'error' => $e->getMessage()]);
            return ApiResponse::serverError('Registration failed', $e);
        }
    }
}

