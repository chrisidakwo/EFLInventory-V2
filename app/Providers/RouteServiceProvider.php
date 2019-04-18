<?php

namespace EFLInventory\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    /**
     * This namespace is EFLInventorylied to your controller routes.
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'EFLInventory\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot() {
        //

        parent::boot();
    }

    /**
     * Define the routes for the EFLInventorylication.
     *
     * @return void
     */
    public function map() {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "api" routes for the EFLInventorylication.
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes() {
        Route::prefix('api')->middleware('api')->namespace($this->namespace)->group(base_path('routes/api.php'));
    }

    /**
     * Define the "web" routes for the EFLInventorylication.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes() {
        Route::middleware('web')->namespace($this->namespace)->group(base_path('routes/web.php'));
    }
}
