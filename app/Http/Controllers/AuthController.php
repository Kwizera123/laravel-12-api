<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6|confirmed',
        // ]);

        $validated = Validator::make($request->all(),[
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:6|confirmed',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }
        try{


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        //return
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ],status: 200);
            //
        } catch(\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 403);
        }

    }
    // Login
    public function login(Request $request) {
        $validated = Validator::make($request->all(),[
             'email' => 'required|string|email',
             'password' => 'required|string|min:6',
        ]);

        if($validated->fails()){
            return response()->json($validated->errors(),403);
        }
        $credentials = ['email' => $request->email,'password'=> $request->password];

        try{
            if(!auth()->attempt($credentials)){
              return response()->json(['error' => 'Invalid Credentials'], 403);  
            }
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

                    //return
        return response()->json([
            'access_token' => $token,
            'user' => $user
            ],status: 200);

        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    //Logout
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

     //return
        return response()->json([
            'message' => 'User has been logged out successfully'
            ],status: 200);
    }
}
