<?php

namespace App\Http\Controllers;

use App\Models\EditModel;
use App\Models\UserModel;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserModel $userModel,
        private readonly EditModel $editModel
    ) {}

    /**
     * @return View
     */
    public function getUsersList(): View
    {
        $users = $this->userService->getList();
        return view('users', ['users' => $users]);
    }

    /**
     * @return View
     */
    public function printCreateForm(): View
    {
        return view('create_user');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function  create(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'unique:users|min:4|max:15|required',
            'email' => 'unique:users|email|required',
            'password' => 'required|min:5'
        ]);

        if (!empty($_FILES) && $_FILES['image']['size'] > 0) {
            $newUser = $this->userModel->createNewUserWithImage();
            if ($newUser) {
                return Redirect::to('/users');
            } else {
                return Redirect::to('/create');
            }
        } else {
            $this->userModel->createNewUser();
            return Redirect::to('/users');
        }
    }

    /**
     * @param int $id
     * @return View
     */
    public function printStatusForm(int $id): View
    {
        $status = $this->userService->getUser($id)->value('status');
        return view('status', ['id' => $id, 'status' => $status]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function setNewStatus(int $id): RedirectResponse
    {
        $status = $_POST['status'];
        $this->editModel->setStatus($status, $id);
        return Redirect::to('users');
    }

    /**
     * @param int $id
     * @return View
     */
    public function printEditForm(int $id): View
    {
        $user = $this->userService->getUser($id);
        return view('edit', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function setNewData(Request $request, int $id): RedirectResponse
    {
        $oldUserName = $this->userService->getUser($id)->value('username');
        $newUserName = $request->get('username');
        if ($oldUserName === $newUserName) {
            $request->validate([
                'username' => 'min:4|max:15|required'
            ]);
            $this->userModel->updateUser($request, $id);
            return Redirect::to('/users');
        }
        $request->validate([
            'username' => 'unique:users|min:4|max:15|required'
        ]);
        $this->userModel->updateUser($request, $id);
        return Redirect::to('/users');
    }

    /**
     * @param int $id
     * @return View
     */
    public function printSecurityForm(int $id): View
    {
        $user = $this->userService->getUser($id);
        return view('security', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function setNewSecurityData(Request $request, int $id): RedirectResponse
    {
        $oldUserEmail = $this->userService->getUser($id)->value('email');
        $newUserEmail = $request->get('email');
        if ($oldUserEmail === $newUserEmail) {
            $request->validate([
                'email' => 'required|email|max:25',
                'password' => 'required|min:5|confirmed',
                'password_confirmation' => 'required'
            ]);
            $this->userModel->updateUser($request, $id);
            return Redirect::to('/users');
        }
        $request->validate([
            'email' => 'unique:users|required|email|max:25',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required'
        ]);
        $this->userModel->updateUser($request, $id);
        return Redirect::to('/users');
    }

    /**
     * @param int $id
     * @return View
     */
    public function printImageForm(int $id): View
    {
        $image = $this->userService->getUser($id)->value('image');
        return view('media', ['id' => $id, 'image' => $image]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function setNewImage(int $id): RedirectResponse
    {
        $newImage = $this->userModel->checkFile($_FILES, $id);
        if ($newImage) {
            return Redirect::to('/users');
        }
        return Redirect::to("/media/{$id}");
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $this->userService->deleteUser($id);
        if ($id == $_SESSION['id']) {
            $_SESSION = [];
            return Redirect::to('/logout');
        }
        $_SESSION['success'] = 'Пользователь удален!';
        return Redirect::to('/users');
    }

    /**
     * @param int $id
     * @return View
     */
    public function printUserProfile(int $id): view
    {
        $user = $this->userService->getUser($id);
        return view('page_profile', ['user' => $user]);
    }
}
