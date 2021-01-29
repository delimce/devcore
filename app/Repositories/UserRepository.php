<?php

namespace App\Repositories;

use App\Models\Users\User;
use Carbon\Carbon as Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
        $result = User::create($data);
        return $result;
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
