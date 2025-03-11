<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function forgot(ForgotRequest $request)
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
         * Todo: Send reset password email
         */

        return response()->json([
            'success' => true,
            'message' => 'Reset password email sent',
            'data' => null
        ]);
    }

    public function reset(ResetRequest $request)
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
         * Todo: Verify reset password token
         */

        // $user->password = Hash::make($request->password);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful',
            'data' => null
        ]);
    }
}
