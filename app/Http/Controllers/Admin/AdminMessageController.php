<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class AdminMessageController extends Controller
{
    public function index() {
        return ContactMessage::all();
    }

    public function show($id) {
        return ContactMessage::findOrFail($id);
    }

    public function destroy($id) {
        ContactMessage::destroy($id);
        return response()->json(['message' => 'Message deleted']);
    }
}

