<?php

namespace App\Repositories;

use App\Models\Manager\Manager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ManagerRepository
{
    /**
     * getById
     *
     * @param  int  $managerId
     * @return Manager
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById(int $managerId): Manager
    {
        return Manager::findOrFail($managerId);
    }

    /**
     * @param array $filters
     * @return Manager|null
     */
    public function getFirstByFilters(array $filters): ?Manager
    {
        return Manager::where($filters)->first();
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public function getByFilters(array $filters): Collection
    {
        return Manager::where($filters)->get();
    }

    /**
     * @param mixed $token
     * @param mixed $newToken
     * @param mixed $password
     * @return bool
     */
    public function changePasswordWithToken($token, $newToken, $password): bool
    {
        Manager::where('token', $token)
            ->update([
                'token' => $newToken,
                'password' => $password
            ]);
        return true;
    }


    /**
     * @param array $data
     * @return Manager
     * @throws Illuminate\Database\QueryException
     */
    public function create(array $data): Manager
    {
        return  Manager::create($data);
    }

    /**
     * getTokenById
     *
     * @param  int $userID
     * @return string
     */
    public function getTokenById($userID)
    {
        return DB::table('manager')->where('id', $userID)->value('token');
    }

    /**
     * @param string $token
     * @return Manager|null
     */
    public function getManagerByToken(string $token): ?Manager
    {
        return Manager::with('company')->firstWhere(["token" => $token, "verified" => 1]);
    }

    /**
     * @param Manager $info
     * @return bool
     */
    public function saveUserInfo(array $info)
    {
        try {
            $user = Manager::find($info["id"]);
            $user->name = $info["name"];
            $user->lastname = $info["lastname"];
            $user->dni = $info["dni"];
            $user->birthdate = $info["birthdate"];
            $user->phone = $info["phone"];
            $user->save();
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }

    /**
     * @param  string $token
     * @param  string $password
     * @return bool
     */
    public function updatePassword(string $token, string $password)
    {
        try {
            Manager::whereToken($token)->update(["password" => $password]);
            return true;
        } catch (QueryException $ex) {
            Log::error($ex);
            return false;
        }
    }
}
