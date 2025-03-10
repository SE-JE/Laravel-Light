<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthenticateController extends Controller
{

    /**
     * Handle an authentication attempt.
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {

            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'data' => null
            ], 500);
        }

        DB::commit();

        /**
         * TODO: Send email
         */

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function resendVerifyEmail(ResendRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        /**
         * TODO: Send email
         */

        return response()->json([
            'success' => true,
            'message' => 'Verification email sent',
            'data' => null
        ]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function verifyEmail(VerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $token = $request->token;

        /**
         * TODO: Verify email token
         */

        // $user->email_verified_at = now();
        // $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email verified',
            'data' => $user
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
            'data' => null
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function me()
    {
        return response()->json([
            'success' => true,
            'message' => 'User details',
            'data' => Auth::user()
        ]);
    }
}
