<?php

namespace App\Repositories;

use App\Models\Users\User;

class UserRepository
{

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

   
    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }

    /**
     * @param int $userId
     * @return User
     */
    public function findById(int $userId): User
    {
        return User::findOrFail($userId);
    }
}
