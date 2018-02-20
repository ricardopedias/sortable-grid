<?php

namespace SortableGrid;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'sortgrid');

        // Em modo de desenvolvimento, as views são sempre apagadas
        if (env('APP_DEBUG') || env('APP_ENV') === 'local') {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }

        \SortableGrid::loadHelpers();

        \SortableGrid::loadBladeDirectives();
    }
}
