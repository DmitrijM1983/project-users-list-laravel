<?php

namespace App\Services;


use \Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param Request $request
     * @return void
     */
    public function registration(Request $request): void
    {
        $params['email'] = $request->get('email');
        $params['password'] = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $this->insert($params);
    }

    /**
     * @param int|string $var
     * @return Collection
     */
    public function getUser(int|string $var): Collection
    {
        if (is_numeric($var)) {
        return DB::table('users')
            ->select('*')
            ->where('id', '=', $var)
            ->get();
        }
        return DB::table('users')
            ->select('*')
            ->where('email', '=', $var)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getList(): Collection
    {
        return DB::table('users')
            ->select('*')
            ->get();
    }

    /**
     * @param array $params
     * @return void
     */
    public function insert(array $params): void
    {
        DB::table('users')->insert($params);
    }

    /**
     * @param string $var
     * @param int $val
     * @param array $updateParam
     * @return void
     */
    public function update(string $var, int $val, array $updateParam): void
    {
        DB::table('users')
            ->where($var, $val)
            ->update($updateParam);
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getUserRole(int $id): Collection
    {
        return DB::table('groups')
            ->select('permissions')
            ->where('id', '=', $id)
            ->get();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        DB::table('users')->where('id', '=', $id)->delete();
        DB::table('user_cookie')->where('user_id', '=', $id)->delete();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getCookie(int $userId): Collection
    {
        return DB::table('user_cookie')
            ->select('hash')
            ->where('user_id', '=', $userId)
            ->get();
    }

    /**
     * @param array $params
     * @return void
     */
    public function insertCookie(array $params): void
    {
        DB::table('user_cookie')->insert($params);
    }
}
