<?php
namespace urlShortenApp\Service;

use urlShortenApp\Model\User;
use urlShortenApp\Repository\UserRepository;

class UserService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser($name, $password)
    {
        $user = new User(null, $name, $password);

        $user = $this->userRepository->saveUser($user);

        return $user;
    }

    public function getUserByName($name)
    {
        return $this->userRepository->getUserByName($name);
    }

    public function updateUser(User $user, $name, $password)
    {
        $user->name = $name;
        $user->password = $password;

        $user = $this->userRepository->saveUser($user);

        return $user;
    }

}