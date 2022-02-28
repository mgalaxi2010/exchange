<?php

namespace App\Services;

use App\Repositories\CoinConvertRepositoryInterface;
use Illuminate\Http\Request;


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

    public function convertCoin(Request $request)
    {
        return $this->coinConvertRepository->convertCoin($request);
    }


}
