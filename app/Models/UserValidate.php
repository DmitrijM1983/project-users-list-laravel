<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValidate extends Model
{
    use HasFactory;

    /**
     * @param string $name
     * @param int $size
     * @return bool
     */
    public function checkImage(string $name, int $size): bool
    {
        if ($name === '') {
            $_SESSION['error'] = "Вы не выбрали файл!";
            return false;
        }
        $name = explode('.', $name);
        $name = $name[1];
        if ($name === 'png' || $name === 'jpg' || $name === 'jpeg' && $size < 9000000) {
            return true;
        } else {
            $_SESSION['error'] = "Файл не соответствует!";
            return false;
        }
    }
}
