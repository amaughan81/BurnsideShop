<?php

namespace bbec\shop\Providers;

use App\User;
use bbec\Shop\Models\Merit;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(dirname(__DIR__).'/views', 'shop');
        // Publish views
        $this->publishes([
            dirname(__DIR__).'/views' => base_path('resources/views/vendor/bbec')
        ]);
        // Publish migrations
        $this->publishes([
            dirname(__DIR__).'/migrations' => base_path('database/migrations'),
            'migrations'
        ]);
        // Publish public directory
        $this->publishes([
            dirname(__DIR__).'/public' => public_path('vendor/bbec/shop'),
        ], 'public');

        //View::share('basketCount', $this->getBasketCount());
        view()->composer('*', function ($view)
        {
            $id = Route::current()->parameter('id');
            //...with this variable
            $view->with('syncMerits', $this->syncMerits($id));
        });

        //$this->showMeritBalance();

        $this->registerHelpers();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include dirname(__DIR__).'/routes.php';
        // Add more controllers here as necessary

        $this->app->make('bbec\Shop\Http\Controllers\CategoriesController');
        $this->app->make('bbec\Shop\Http\Controllers\ProductsController');
        $this->app->make('bbec\Shop\Http\Controllers\PhotosController');
        $this->app->make('bbec\Shop\Http\Controllers\ShopController');
        $this->app->make('bbec\Shop\Http\Controllers\CartController');
        $this->app->make('bbec\Shop\Http\Controllers\MeritsController');
        $this->app->make('bbec\Shop\Http\Controllers\OrdersController');
        $this->app->make('bbec\Shop\Http\Controllers\CouponsController');
        $this->app->make('bbec\Shop\Http\Controllers\UsersController');
    }

    /**
     * Register HTTP Helpers - Got to be a better way to do this in packages
     */
    private function registerHelpers() {
        $helperDir = dirname(__DIR__).'/Http/Helpers';
        $helperFiles = scandir($helperDir);
        foreach($helperFiles as $file) {
            if($file != "." && $file != "..") {
                require $helperDir . '/' . $file;
            }
        }
    }

    private function syncMerits($id=0)
    {
        if(!\Auth::check()) {
            return null;
        }
        if($id == 0) {
            $user = \Auth::user();
        } else {
            $user = User::findOrFail($id);
        }
        if ($user->role == "student" || $user->role == "manager") {
            $merit = new Merit();
            $merits = $merit->getCurrentMeritValue($user->id);

            // If no merits are present enqueue the request to SIMS
            if (count($merits) == 0) {
                return $user->username;
            }
        }

        return null;
    }
}
