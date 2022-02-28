<?php

namespace App\Services;


use App\Repositories\Eloquent\WalletRepository;
use App\Repositories\WalletRepositoryInterface;

class WalletService
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var WalletService
     */

    public function __construct(WalletRepositoryInterface $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function deposit($request)
    {
        return $this->walletRepository->deposit($request);
    }
}
