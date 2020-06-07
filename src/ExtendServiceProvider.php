<?php

namespace SmallRuralDog\LaravelVueAdminLogs;

use Illuminate\Support\ServiceProvider;
use SmallRuralDog\Admin\Admin;

class ExtendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Admin::script('SmallRuralDog-laravel-vue-admin-logs', __DIR__.'/../dist/js/extend.js');
        Admin::style('SmallRuralDog-laravel-vue-admin-logs', __DIR__.'/../dist/css/extend.css');

        Admin::css("https://cdn.bootcdn.net/ajax/libs/font-awesome/5.13.0/css/fontawesome.min.css");

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouter(); //
    }
    /**
     * 注册路由
     *
     * @author osi
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->namespace('SmallRuralDog\LaravelVueAdminLogs\Controllers')->group(__DIR__ . '/../routes/route.php');
        } else {
            $this->loadRoutesFrom(__DIR__ . '/../routes/route.php');
        }
    }
}
