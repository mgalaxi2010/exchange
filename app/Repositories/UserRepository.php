<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }

    public function coins($request)
    {
        return $this->findBySlug($request['slug'])->with('coins')->get();
    }

    public function findBySlug($slug)
    {
        return $this->user->findBySlug($slug);
    }
}
