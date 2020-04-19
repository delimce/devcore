<?php

namespace App\Services\Manager;

use App\Models\Manager\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class UserService
{

    /**
     * checkEmail
     * @param  mixed $email
     * @return bool
     */
    public function checkEmail(string $email)
    {
        $exits = User::whereEmail($email)->count();
        return ($exits > 0);
    }

    /**
     * @return bool|array
     */
    public function addUser(array $user)
    {

        $password = Hash::make($user['password']);
        try {
            User::create(
                [
                    'name' => $user['name'],
                    'lastname' => $user['lastname'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'password' => $password
                ]
            );
        } catch (QueryException $ex) {
            return false;
        }

        ///email to sending
        return __('manager.created');
    }
}
