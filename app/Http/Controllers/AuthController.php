<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // public function loginUser(Request $req)
    // {
    //     $email = $req->input('loginEmail');
    //     $password = $req->input('loginPassword');

    //     $hashPassword = Hash::make($password);
    //     $userList = DB::select("SELECT * FROM users
    //                                     WHERE email = ? AND PASSWORD = ?", [$email, $hashPassword]);
        
    //     return $userList;
    // }
}
