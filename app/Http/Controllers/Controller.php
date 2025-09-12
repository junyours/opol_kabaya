<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\EmailVerification;
use Carbon\Carbon;
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
}
