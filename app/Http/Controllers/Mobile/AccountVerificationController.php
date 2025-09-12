<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountVerificationController extends Controller
{
    public function updateIdentity(Request $request)
    {
        $user_id = $request->user()->id;

        $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'birth_date' => ['required'],
            'phone_number' => ['required', 'unique:users,phone_number,' . $user_id],
            'sex' => ['required'],
        ]);

        User::find($user_id)
            ->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix === "N/A" ? null : $request->suffix,
                'birth_date' => Carbon::parse($request->birth_date)
                    ->timezone('Asia/Manila')
                    ->toDateString(),
                'phone_number' => $request->phone_number,
                'sex' => $request->sex,
            ]);
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'province' => ['required'],
            'municipality' => ['required'],
            'barangay' => ['required'],
            'street_name' => ['required'],
            'postal_code' => ['required'],
        ]);

        User::find($request->user()->id)
            ->update([
                'province' => $request->province,
                'municipality' => $request->municipality,
                'barangay' => $request->barangay,
                'street_name' => $request->street_name,
                'postal_code' => $request->postal_code,
            ]);
    }
}
