<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req) {

        $data = $req->validate([
            'username' => 'required|unique:users|min:4|max:60',
            'password' => 'required|min:8|max:216'
        ]);

        $user = new User;
        $user->username = $req->username;
        $user->password = bcrypt($req->password);
        $user->save();

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function login(Request $req) {

        $data = $req->validate([
            'username' => 'required|min:4|max:60',
            'password' => 'required|min:8|max:216'
        ]);

        $user = User::where('username', $data['username'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Wrong username or password'
            ]);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token
        ], 201);
    }
}
