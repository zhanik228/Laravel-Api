<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(Request $req, $username) {
        $user = User::where('username', $username)->first();
        return response()->json([
            'username' => $req->username,
            'registeredTimestamp' => $user->created_at,
            'authoredGames' => ''
        ]);
    }
}
