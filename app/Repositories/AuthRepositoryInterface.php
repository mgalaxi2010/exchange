<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
    public function getUser();

    public function destroyCookie();

    public function setCookie($user);

    public function AuthenticateUser($request);

    public function createUser($request);
}
