<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if (Hash::check($request->hash, $user->getEmailVerificationHash())) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();

                event(new Verified($user));
            }

            return response()->json([
                'message' => 'Email verified successfully'
            ]);
        }

        return response()->json([
            'message' => 'Invalid verification link',
        ], 400);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your email is already verified.']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }

}
