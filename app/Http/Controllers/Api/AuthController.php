<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\SendVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8|max:50'
        ]);
        $fullName = $request->first_name . ' ' . $request->last_name;
        $fullName = Str::title($request->first_name . ' ' . $request->last_name);

        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'visitor' // default role
        ]);
        // Generate a random 6-digit code
    $verificationCode = random_int(100000, 999999);
    $user->verification_code = $verificationCode;
    $user->save();

    // Send verification email
    Mail::to($user->email)->send(new SendVerificationCode($user));

    return response()->json([
        'message' => 'Registration successful. Please check your email for the verification code.',
    ]);
    }


    public function verifyCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'verification_code' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    if ($user->verification_code == $request->verification_code) {
        $user->is_verified = true;
        $user->verification_code = null; // clear the code
        $user->save();

        return response()->json(['message' => 'Your email has been verified successfully.']);
    } else {
        return response()->json(['message' => 'Invalid verification code.'], 400);
    }
}

public function resendVerificationCode(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user->is_verified) {
        return response()->json(['message' => 'User already verified.'], 400);
    }

    // Generate a new code
    $user->verification_code = rand(100000, 999999);
    $user->save();

    // Send email using your existing Mailable
    Mail::to($user->email)->send(new SendVerificationCode($user));

    return response()->json(['message' => 'Verification code resent to your email.']);
}



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }


 if ($user->fcm_token) {
        $factory = (new Factory)
            ->withServiceAccount(base_path('storage/app/firebase/firebase_credentials.json')); 

        $messaging = $factory->createMessaging();

        $message = CloudMessage::withTarget('token', $user->fcm_token)
            ->withNotification(FirebaseNotification::create(
                'أهلاً من جديد 🎉',
                "تم تسجيل دخولك بنجاح 😊"
            ));

        $messaging->send($message);
    }


        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => $user

        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }


    public function saveFcmToken(Request $request)
{
    $request->validate([
        'fcm_token' => 'required|string',
    ]);

    $user = auth()->user(); 
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json(['message' => 'Token saved successfully']);
}

}
