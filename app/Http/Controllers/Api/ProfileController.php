<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller {
    public function store( StoreProfileRequest $request ) {
        $user_id = Auth::user()->id;
        $validatedData = $request->validated();
        $validatedData[ 'user_id' ] = $user_id;
        if ( $request->hasFile( 'image' ) ) {
            $path = $request->file( 'image' )->store( 'photos', 'public' );
            $validatedData[ 'image' ] = $path;

        }
        $profile = Profile::create( $validatedData );
        return response()->json( [
            'message'=>'Profile created succesfully',
            'profile'=>$profile,
        ], 201 );
    }

    public function index() {
        $profile = Auth::user()->profile;
        return response()->json( $profile, 200 );
    }

    public function show( Request $request, $id ) {
        $user_id = Auth::user()->id;
        $profile = Profile::findOrFail( $id );
        if ( $profile->user_id !== $user_id )
        return response()->json( [ 'message'=>'Unauthurized' ], 403 );
        $profile = Profile::where( 'user_id', $user_id )->firstOrFail();
        return response()->json( $profile, 200 );
    }

    public function update( UpdateProfileRequest $request, $id ) {
        try {
            $user_id = Auth::user()->id;
            $profile = Profile::findOrFail( $id );
            if ( $profile->user_id !== $user_id )
            return response()->json( [ 'message'=>'Unauthurized' ], 403 );
            // $profile = Profile::where( 'user_id', $user_id )->firstOrFail();
            $profile->update( $request->all() );
            return response()->json( $profile, 200 );
        } catch ( ModelNotFoundException $e ) {
            return response()->json( [
                'Error'=> 'Profile Not Found',
                'Details'=>$e->getMessage() ], 404 );
            }
        }
    }
