<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\EmailVerification;
use Carbon\Carbon;
use Http;
use Mail;

abstract class Controller
{
    public function otp($user)
    {
        do {
            $otp = random_int(100000, 999999);
        } while (
            EmailVerification::query()
                ->where('otp', $otp)
                ->exists()
        );

        EmailVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expired_at' => Carbon::now()->addMinutes(3),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));
    }

    public function token()
    {
        $client_id = config('services.google.client_id');
        $client_secret = config('services.google.client_secret');
        $refresh_token = config('services.google.refresh_token');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get Google access token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }
}
