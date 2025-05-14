<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use App\Models\PasswordResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller {

    public function sendResetCode( Request $request ) {
        $request->validate( [
            'email' => 'required|email|exists:users,email',
        ] );

        $code = random_int( 100000, 999999 );


        PasswordResetCode::where( 'email', $request->email )->delete();

        PasswordResetCode::create( [
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes( 3 ),
        ] );

        Mail::to( $request->email )->send( new PasswordResetCodeMail( $code ) );

        return response()->json( [ 'message' => 'Verification code sent to your email.' ] );
    }


    // public function verifyResetCode(Request $request)
    // {
    //     $request->validate([
    //         'code' => 'required|string',
    //     ]);

    //     // Find the code in the database
    //     $resetCode = PasswordResetCode::where('code', $request->code)->first();

    //     // Validate the code and its expiration
    //     if (!$resetCode || now()->gt($resetCode->expires_at)) {
    //         return response()->json(['message' => 'Invalid or expired code.'], 422);
    //     }

    //     // If needed, return the associated email to proceed with resetting the password
    //     return response()->json([
    //         'message' => 'Code verified.',
    //         'email' => $resetCode->email
    //     ]);
    // }
    public function verifyResetCode(Request $request)
{
    $request->validate([
        'code' => 'required|string',
    ]);

    $resetCode = PasswordResetCode::where('code', $request->code)->first();

    if (!$resetCode || now()->gt($resetCode->expires_at)) {
        return response()->json(['message' => 'Invalid or expired code.'], 422);
    }

    // Generate a simple session token (could also use JWT or Laravel temporary signed route)
    $resetToken = base64_encode($resetCode->email . '|' . now());

    // Store the token in cache temporarily (valid for 5 minutes)
    \Cache::put('reset_token_' . $resetToken, $resetCode->email, now()->addMinutes(5));

    return response()->json([
        'message' => 'Code verified.',
        'reset_token' => $resetToken
    ]);
}



    // public function resetPassword( Request $request ) {
    //     $request->validate( [
    //         'email' => 'required|email',
    //         'code' => 'required',
    //         'password' => 'required|min:6|confirmed'
    //     ] );

    //     $code = PasswordResetCode::where( 'email', $request->email )
    //     ->where( 'code', $request->code )
    //     ->first();

    //     if ( !$code || now()->gt( $code->expires_at ) ) {
    //         return response()->json( [ 'message' => 'Invalid or expired code.' ], 422 );
    //     }

    //     $user = User::where( 'email', $request->email )->first();
    //     $user->password = Hash::make( $request->password );
    //     $user->save();

    //    $code->delete();

    //     return response()->json( [ 'message' => 'Password successfully reset.' ] );
    // }

    public function resetPassword(Request $request)
{
    $request->validate([
        'reset_token' => 'required',
        'password' => 'required|min:6|confirmed'
    ]);

    $email = \Cache::get('reset_token_' . $request->reset_token);

    if (!$email) {
        return response()->json(['message' => 'Reset token is invalid or expired.'], 403);
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    // Clean up
    \Cache::forget('reset_token_' . $request->reset_token);
    PasswordResetCode::where('email', $email)->delete();

    return response()->json(['message' => 'Password successfully reset.']);
}

}
