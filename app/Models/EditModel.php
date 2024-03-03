<?php

namespace App\Models;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditModel extends Model
{
    use HasFactory;

    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * @param string $status
     * @param int|null $id
     * @return string|null
     */
    public function setStatus(string $status, int $id = null): ?string
    {
        if ($status === 'Онлайн') {
            $newStatus = 'online';
        }
        if ($status === 'Отошел') {
            $newStatus = 'moved away';
        }
        if ($status === 'Не беспокоить') {
            $newStatus = 'do not disturb';
        }
        if ($id === null) {
            return $newStatus;
        }
        $this->userService->update('id', $id, ['status'=>$newStatus]);
        $_SESSION['success'] = 'Профиль успешно обновлен!';
        return null;
    }

    /**
     * @param string $name
     * @param string $tmp
     * @param int|string $var
     * @return void
     */
    public function setNewImage(string $name, string $tmp, int|string $var): void
    {
        $user = $this->userService->getUser($var);
        if ($user) {
            $fileName = $user->value('image');
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
        $image = '../resources/image/avatar-' . uniqid() . '.' . $name;
        move_uploaded_file($tmp, $image);
        $this->userService->update('id', $user->value('id'), ['image'=>$image]);
    }
}
