<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function findBySlug($slug);

    public function coins();

}
