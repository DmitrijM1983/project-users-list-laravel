<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    public function __construct(
        private readonly EditModel $editModel,
        private readonly UserService $userService,
        private readonly UserValidate $userValidate
    ) { }

    /**
     * @param string $passwordHash
     * @param string $password
     * @return bool
     */
    public function checkUser(string $passwordHash, string $password): bool
    {
        if (password_verify($password, $passwordHash)) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function createNewUserWithImage(): bool
    {
        $checkFile = $this->checkFile($_FILES);
        if ($checkFile) {
            $this->createNewUser();
            $this->editModel->setNewImage($_FILES['image']['name'], $_FILES['image']['tmp_name'], $_POST['email']);
            return true;
        }
        return false;
    }

    /**
     * @param array $fileData
     * @param int|string|null $var
     * @return bool
     */
    public function checkFile(array $fileData, int|string $var = null): bool
    {
        $name = $fileData['image']['name'];
        $tmp = $fileData['image']['tmp_name'];
        $size = $fileData['image']['size'];
        $checkImage =  $this->userValidate->checkImage($name, $size);
        if ($checkImage) {
            $this->editModel->setNewImage($name, $tmp, $var);
            $_SESSION['success'] = 'Профиль успешно обновлен!';
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    public function createNewUser(): void
    {
        $params = $_POST;
        $params['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $status = $_POST['status'];
        $status = $this->editModel->setStatus($status);
        $params['status'] = $status;
        unset($params['_token']);
        $this->userService->insert($params);
        $_SESSION['success'] = 'Пользователь успешно добавлен!';
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function getRole(string $email): mixed
    {
        $roleId = $this->userService->getUser($email)->value('group_id');
        return $this->userService->getUserRole($roleId)->value('permissions');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateUser(Request $request, int $id): void
    {
        $params = $request->all();
        unset($params['_token']);
        if(isset($params['password_confirmation'])) {
            unset($params['password_confirmation']);
        }
        if(isset($params['password'])) {
            $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
        }
        $this->userService->update('id', $id, $params);
        $_SESSION['success'] = 'Профиль успешно обновлен!';
    }
}
