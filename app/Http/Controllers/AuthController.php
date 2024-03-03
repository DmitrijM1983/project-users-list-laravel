<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
      private readonly UserService $userService,
      private readonly UserModel   $userModel
    ) { }

    /**
     * @return View
     */
    public function printRegForm(): View
    {
        return view('page_register');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function registration(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:20|unique:users',
            'password' => 'required|min:5'
        ]);

        $this->userService->registration($request);
        $_SESSION['email'] = $request->get('email');
        $_SESSION['success'] = 'Вы успешно зарегистрировались!';
        return Redirect::to('/login');
    }

    /**
     * @return View
     */
    public function printLoginForm(): View
    {
        return view('page_login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
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
            if(isset($_POST['remember'])) {
                $cookie = $this->userService->getCookie($user->value('id'));
                if (empty($cookie->all())) {
                    $cookie = hash('sha256', uniqid());
                    $this->userService->insertCookie(['user_id' => $user->value('id'), 'hash' => $cookie]);
                } else {
                    $cookie = $cookie->value('hash');
                }
                setcookie('hash', $cookie, time() + 604800, '/');
            }
            $_SESSION['id'] = $user->value('id');
            $_SESSION['name'] = $user->value('username');
            $role = $this->userModel->getRole($user->value('email'));
            $_SESSION['role'] = $role;
            return Redirect::to('/users');
        } else {
            $_SESSION['error'] = 'Логин или пароль не верны!';
            return Redirect::to('/login');
        }
    }

    /**
     * @return View
     */
    public function logout(): View
    {
        $_SESSION = [];
        return view('start_page');
    }
}
