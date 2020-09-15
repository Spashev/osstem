<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Manager;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['admin.users', 'admin.index'], function ($view) {
            $view->with([
                'users' => User::all(),
                'managers' => Manager::all(),
                'customers' => Customer::all()
            ]);
        });
    }
}