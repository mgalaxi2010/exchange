<?php

namespace App\Services;

use App\Repositories\WalletRepository;

class WalletService
{
    /**
     * @var WalletRepository
     */
    protected $walletRepository;

    /**
     * @var WalletService
     */

    public function __construct(WalletRepository $walletRepository)
    {

        $this->walletRepository = $walletRepository;
    }

    public function deposit($request)
    {
        return $this->walletRepository->deposit($request);
    }
}
