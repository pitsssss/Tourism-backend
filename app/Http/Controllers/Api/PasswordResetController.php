<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    public function sendResetCode(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a 6-digit random code
        $code = rand(100000, 999999);

        // Store the code temporarily (use database or cache if needed)
        // Example: Cache::put('password_reset_' . $request->email, $code, now()->addMinutes(10));

        // Send the reset code to the user's email
        Mail::to($request->email)->send(new PasswordResetCodeMail($code));

        return response()->json(['message' => 'Password reset code sent to your email.'], 200);
    }
}
