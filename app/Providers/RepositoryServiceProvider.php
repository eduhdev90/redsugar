<?php

namespace App\Providers;

use App\Repositories\FavoriteRepository;
use App\Repositories\FavoriteRepositoryInterface;
use App\Repositories\Plan\PriceRepository;
use App\Repositories\Plan\PriceRepositoryInterface;
use App\Repositories\Plan\ProductRepository;
use App\Repositories\Plan\ProductRepositoryInterface;
use App\Repositories\Plan\SubscriptionActiveViewRepository;
use App\Repositories\Plan\SubscriptionActiveViewRepositoryInterface;
use App\Repositories\Plan\SubscriptionRepository;
use App\Repositories\Plan\SubscriptionRepositoryInterface;
use App\Repositories\ProfileViewRepository;
use App\Repositories\ProfileViewRepositoryInterface;
use App\Repositories\User\ProfileAvailableRepository;
use App\Repositories\User\ProfileAvailableRepositoryInterface;
use App\Repositories\User\RegisterRepository;
use App\Repositories\User\RegisterRepositoryInterface;
use App\Repositories\User\UserPhotoRepository;
use App\Repositories\User\UserPhotoRepositoryInterface;
use App\Repositories\User\UserProfileRepository;
use App\Repositories\User\UserProfileRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public $bindings = [
        RegisterRepositoryInterface::class => RegisterRepository::class,
        UserProfileRepositoryInterface::class => UserProfileRepository::class,
        UserPhotoRepositoryInterface::class => UserPhotoRepository::class,
        FavoriteRepositoryInterface::class => FavoriteRepository::class,
        ProfileViewRepositoryInterface::class => ProfileViewRepository::class,
        ProfileAvailableRepositoryInterface::class => ProfileAvailableRepository::class,
        ProductRepositoryInterface::class => ProductRepository::class,
        PriceRepositoryInterface::class => PriceRepository::class,
        SubscriptionRepositoryInterface::class => SubscriptionRepository::class,
        SubscriptionActiveViewRepositoryInterface::class => SubscriptionActiveViewRepository::class
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
