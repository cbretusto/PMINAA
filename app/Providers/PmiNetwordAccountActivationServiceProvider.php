<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\UserManagementInterface;
use App\Interfaces\AccessInterface;
use App\Interfaces\PminaaRequestInterface;

use App\Repositories\UserManagementRepository;
use App\Repositories\AccessRepository;
use App\Repositories\PminaaRequestRepository;


Barryvdh\DomPDF\ServiceProvider::class;

class PmiNetwordAccountActivationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserManagementInterface::class, UserManagementRepository::class);
        $this->app->bind(AccessInterface::class, AccessRepository::class);
        $this->app->bind(PminaaRequestInterface::class, PminaaRequestRepository::class);
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
