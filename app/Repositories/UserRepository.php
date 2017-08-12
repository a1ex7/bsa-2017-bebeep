<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\User;

class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user) : User
    {
        $user->save();

        return $user;
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return User
     */
    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

}