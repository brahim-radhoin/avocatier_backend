<?php

namespace App\Http\Controllers;

use App\Models\Avo;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // public function verify(EmailVerificationRequest $request)
    // {
    //     $request->fulfill();

    //     return redirect()->to('/home');
    // }


    public function verify($id, $hash)
{
    $avo = Avo::findOrFail($id);

    if (sha1($avo->email) !== $hash) {
        // The hash does not match the avo's email, return an error response
        return abort(403, 'Invalid verification link');
    }

    // The hash matches the avo's email, mark the avo as verified
    $avo->email_verified_at = now();
    $avo->save();

    // Redirect to the home page, return a success response, or do something else
    return response()->json('Account Verified!');
}
}