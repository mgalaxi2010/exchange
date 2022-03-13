<?php

namespace App\Repositories\Eloquent;


use App\Models\Transaction;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    function getModel(): Model
    {
        return new User();
    }

    public function userWallet()
    {
        return Auth::user()->coins()->get();
    }

    public function userCoinBalance(int $id, string $coin)
    {
        return $this->getModel()->query()->findOrFail($id)->coins()->where('coins.symbol', strtoupper($coin))->first();
    }

    public function findById(int $id)
    {
        return $this->getModel()->query()->findOrFail($id);
    }

    public function updateUserWallet($data)
    {

        $user = $this->findById($data['user_id']);
        $new_balance = $data['type'] == Transaction::DEPOSIT ? floatval($data['amount']) + floatval($data['last_balance']) : floatval($data['last_balance']) - floatval($data['amount']);
        if ($data['isNew']) {
            $wallet = $user->coins()->wherePivot('coin_id', $data['coin_id'])->updateExistingPivot($data['coin_id'], ['users_coins.amount' => $new_balance], false);
        } else {
            $wallet = $user->coins()->attach([$data['coin_id'] => ["amount" => $new_balance]]);
        }
        return $wallet;
    }

    public function getBrokerUser()
    {
        return $this->getModel()->query()->where('email', '=', 'broker@exchange.com')->first();
    }

    public function orders()
    {
        try {
            $orders =  $this->getModel()->join('orders', 'orders.user_id', '=', 'users.id')
                ->join('coins as from', 'orders.from_coin_id', '=', 'from.id')
                ->join('coins as to', 'orders.to_coin_id', '=', 'to.id')
                ->where('users.id','=', Auth::id())
                ->select(
                    'orders.type as type',
                    'from.symbol as from_coin',
                    'orders.from_amount',
                    'to.symbol as to_coin',
                    'orders.to_amount',
                    'orders.created_at'
                )->get();
            $result = [
                'status'=>Response::HTTP_OK,
                'orders'=>$orders
            ];
        }catch (\Exception $e){
            $result = [
                'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                'error'=>$e->getMessage()
            ];
        }
        return $result;

    }

}
