<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\governorates;
use Illuminate\Http\Request;

class GovernoratesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $governorates = governorates::select('id', 'name')->get();
        return response()->json($governorates);
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(governorates $governorates)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(governorates $governorates)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, governorates $governorates)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(governorates $governorates)
    {
        //
    }
}
