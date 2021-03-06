<?php

namespace App\Providers;

use App\Comment;
use App\Post;
use App\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('pages._sidebar', function($view) {
            $view->with('popularPosts', Post::getPopular());
            $view->with('featuredPosts', Post::getFeatured());
            $view->with('recentPosts', Post::getRecent());
            $view->with('categories', Category::all());
        });

        view()->composer('admin.sidebar', function($view) {
            $view->with('newComments', Comment::where('status', 0)->count());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
