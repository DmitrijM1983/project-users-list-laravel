<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    public function getUsersList()
    {
        $users = $this->userService->getList();
        return view('users', ['users' => $users]);
    }
}
