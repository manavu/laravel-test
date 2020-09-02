<?php

namespace App\Providers;

use App\Services\IPostService;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // コンテナからサービスを取得するためにインターフェースと具象をバインド
        // $this->app->bind(IPostService::class, PostService::class);
        $this->app->bind(IPostService::class, function ($app) {
            return new PostService();
        });

        // 使いたい場合は、$service = $app->make(IPostService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
