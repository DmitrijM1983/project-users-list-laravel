<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
      public function __construct(
          private readonly UserService $userService,
          private readonly UserModel   $userModel
      ) { }

    public function printRegForm()
    {
        return view('page_register');
    }

    public function registration(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:20|unique:users',
            'password' => 'required|min:5'
        ]);

        $this->userService->registration($request);
        return Redirect::to('/login');
    }

    public function printLoginForm()
    {
        return view('page_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = $this->userService->getUser($request->get('email'));
        if ($user) {
            $checkUser = $this->userModel->checkUser($user->value('password'), $request->get('password'));
        }
        if ($checkUser) {
            return Redirect::to('/users');
        }
    }
}
