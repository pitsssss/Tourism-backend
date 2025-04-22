<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Request;

class AdminBookingController extends Controller
{
    public function index() {
        return Booking::with(['user', 'hotel'])->get();
    }

    public function show($id) {
        return Booking::with(['user', 'hotel'])->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $booking = Booking::findOrFail($id);
        $booking->update($request->all());
        return $booking;
    }

    public function destroy($id) {
        Booking::destroy($id);
        return response()->json(['message' => 'Booking deleted']);
    }
}
