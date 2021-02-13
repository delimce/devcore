<?php

namespace App\Repositories;

use App\Models\Users\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository
{

    /**
     * createUser
     *
     * @param  array $data
     * @return User
     */
    public function createUser(array $data)
    {
        $data["password"] = Hash::make($data["password"]);
        return User::create($data);
    }


    /**
     * doLogin
     *
     * @param  array $credentials
     * @return array
     */
    public function doLogin(array $credentials)
    {
        $result = ["ok" => false, "message" => ""];
        $user = User::whereEmail($credentials['email'])->first();
        if (is_null($user)) {
            $result["message"] = __('errors.login.email');
            return $result;
        }

        if (!$user->active) {
            $result["message"] = __('errors.login.disable');
            return $result;
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            $result["message"] = __('errors.login.password');
            return $result;
        }

        $result["ok"] = true;
        $result["data"] = $user;
        return $result;
    }



    /**
     * activateUser
     *
     * @param  int $userId
     * @return bool
     */
    public function activateUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->active = 1;
            $user->verified = 1;
            $user->save();
            return true;
        } catch (Exception $ex) {
            Log::error($ex);
            return false;
        }
    }
}
