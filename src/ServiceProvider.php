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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'sortablegrid');

        // Em modo de desenvolvimento, 
        // as rotas de teste estão disponiveis
        if (env('APP_DEBUG') || env('APP_ENV') === 'local') {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }

        // php artisan vendor:publish --tag=laracl-config
        $this->publishes([__DIR__.'/config/sortablegrid.php' => config_path('sortablegrid.php')], 'sortablegrid-config');

        \SortableGrid::loadHelpers();

        \SortableGrid::loadBladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Adiciona as configurações padrões no namespace sortablegrid
        $this->mergeConfigFrom(__DIR__.'/config/sortablegrid.php', 'sortablegrid');
    }
}
