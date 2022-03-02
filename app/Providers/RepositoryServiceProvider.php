<?php

namespace App\Providers;

use App\Repositories\AuthRepositoryInterface;
use App\Repositories\CoinConvertRepositoryInterface;
use App\Repositories\CoinRepositoryInterface;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\CoinConvertRepository;
use App\Repositories\Eloquent\CoinRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WalletRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind(CoinRepositoryInterface::class, CoinRepository::class);
        $this->app->bind(CoinConvertRepositoryInterface::class, CoinConvertRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
