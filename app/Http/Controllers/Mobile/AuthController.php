<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 422);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->password) {
            return response()->json(['message' => 'The email address is invalid.'], 422);
        }

        $this->otp($user);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!$user->password) {
                $user->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'suffix' => $request->suffix === "N/A" ? null : $request->suffix,
                ]);
            } else {
                $request->validate([
                    'email' => ['unique:users,email'],
                ]);
            }
        } else {
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix === "N/A" ? null : $request->suffix,
                'email' => $request->email,
            ]);
        }

        $this->otp($user);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6', 'numeric'],
        ]);

        $record = EmailVerification::whereHas('user', function ($query) use ($request) {
            $query->where('email', $request->email);
        })
            ->latest()
            ->first();

        if ($record->expired_at->isPast()) {
            return response()->json(['message' => 'OTP expired.'], 400);
        }

        if ($record->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP.'], 422);
        }

        $record->delete();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

    public function resendOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $this->otp($user);
    }

    public function createPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::default()],
        ]);

        $user = User::find($request->user()->id)->first();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();
    }
}
