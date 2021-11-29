<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Log;

class UserService
{

    /** @var UserRepository $userRepository*/
    protected $userRepository;

    function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param  array $data
     * @return User
     */
    public function createUser(array $data)
    {
        $data["password"] = Hash::make($data["password"]);
        return $this->userRepository->create($data);
    }


    /**
     * @param array $credentials
     * @return array
     */
    public function doLogin(array $credentials): array
    {
        $result = ["ok" => false, "message" => ""];
        $user = $this->userRepository->getByEmail($credentials['email']);
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
    public function activateUser(int $userId): bool
    {
        try {
            $user = $this->userRepository->findById($userId);
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
