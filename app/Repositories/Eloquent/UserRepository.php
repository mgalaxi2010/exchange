<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\WalletApiResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }


    public function findBySlug($slug)
    {
        return $this->model->findBySlug($slug);
    }

    public function coins()
    {
        try {
            $result = [
                'status' => Response::HTTP_OK,
                'coins' => WalletApiResource::collection($this->model->getUserCoins())
            ];
        } catch (\Exception $e) {
            $result = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $e->getMessage()
            ];
        }
        return $result;
    }
}
