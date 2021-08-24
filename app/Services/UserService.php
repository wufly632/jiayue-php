<?php


namespace App\Services;


use App\Model\User;
use HyperfExt\Hashing\Driver\BcryptDriver;

class UserService
{

    public function registerUser($userData)
    {
        $userData['password'] = (new BcryptDriver())->make($userData['password']);

        $user = new User();
        $user->fill($userData);
        $user->save();

        return $user;
    }
}
