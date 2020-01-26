<?php

namespace App\Services\Manager;

use App\Models\Manager\User;
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


    public function addUser(array $user)
    {

        $password = Hash::make($user['password']);
        User::create(['name' => $user['name'], 'email' => $user['email'], 'password' => $password]);
        
        ///email to sending

        return __('manager.created');

    }
}
