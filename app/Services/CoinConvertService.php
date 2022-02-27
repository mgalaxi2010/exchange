<?php

namespace App\Services;

use App\Repositories\CoinConvertRepositoryInterface;

class CoinConvertService
{

    /**
     * @var CoinConvertRepositoryInterface
     */
    private $coinConvertRepository;

    public function __construct(CoinConvertRepositoryInterface $coinConvertRepository)
    {

        $this->coinConvertRepository = $coinConvertRepository;
    }
}
