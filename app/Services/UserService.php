<?php

namespace App\Services;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function registration(Request $request)
    {
        DB::table('users')->insert([
            'email' => $request->get('email'),
            'password' => password_hash($request->get('password'), PASSWORD_DEFAULT)
        ]);
    }

    public function getUser($email)
    {
        return DB::table('users')
            ->select('*')
            ->where('email', '=', $email)
            ->get();
    }

    public function getList()
    {
        return DB::table('users')
            ->select('*')
            ->get();
    }
}
