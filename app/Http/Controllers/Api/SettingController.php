<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingsPage;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * عرض صفحة إعدادات حسب المفتاح واللغة
     */
    public function show(Request $request, $key)
    {
        $lang = $request->get('lang', 'en'); // اللغة الافتراضية: إنجليزي

        $page = SettingsPage::where('key', $key)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $content = $lang === 'ar' ? $page->content_ar : $page->content_en;

        return response()->json([
            'key' => $page->key,
            'content' => $content,
        ]);
    }
}
