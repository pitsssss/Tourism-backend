<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // جلب بروفايل المستخدم الحالي
    public function index() {
        $profile = Auth::user()->profile;

        if (!$profile) {
            // لو البروفايل غير موجود، أنشئ بروفايل فارغ تلقائيًا
            $profile = Profile::create([
                'user_id' => Auth::id(),
                'date_of_birth' => null,
                'phoneNumber' => null,
                'address' => null,
                'image' => null,
            ]);
        }

        $data = [
            'user_name' => $profile->user->name,
            'user_email' => $profile->user->email,
            'date_of_birth' => $profile->date_of_birth,
            'phoneNumber' => $profile->phoneNumber,
            'address' => $profile->address,
            'image' => $profile->image,
        ];

        return response()->json($data, 200);
    }



    // تحديث البروفايل
    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        // اجلب البيانات المرسلة من المستخدم
        $data = $request->validated();

        // منع تعديل الاسم والإيميل
        unset($data['user_name']);
        unset($data['user_email']);

        // لو فيه صورة جديدة
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('photos', 'public');
            $data['image'] = $path;
        }

        $profile->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => [
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'date_of_birth' => $profile->date_of_birth,
                'phoneNumber' => $profile->phoneNumber,
                'address' => $profile->address,
                'image' => $profile->image,
            ]
        ], 200);
    }
}
