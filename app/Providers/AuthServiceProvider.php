<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Policies\ArticlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Tag::class => TagPolicy::class,
        Category::class => CategoryPolicy::class,
        Article::class => ArticlePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('superadministrador')) {
                return true;
            }
        });

        Gate::define('management-operations', function ($user) {
            if ($user->hasRole(['superadministrador', 'editor'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-laratrust', function ($user) {
            if ($user->isAbleTo(['laratrust-create', 'laratrust-read', 'laratrust-delete', 'laratrust-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-articles', function ($user) {
            if ($user->isAbleTo(['articles-create', 'articles-read', 'articles-delete', 'articles-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-categories', function ($user) {
            if ($user->isAbleTo(['categories-create', 'categories-read', 'categories-delete', 'categories-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-tags', function ($user) {
            if ($user->isAbleTo(['tags-create', 'tags-read', 'tags-delete', 'tags-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-users', function ($user) {
            if ($user->isAbleTo(['users-create', 'users-read', 'users-delete', 'users-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-laratrust', function ($user) {
            if ($user->isAbleTo(['laratrust-create', 'laratrust-read', 'laratrust-delete', 'laratrust-update'])) {
                return true;
            }
            return false;
        });

        Gate::define('management-profile', function ($user) {
            if ($user->isAbleTo(['profile-update', 'profile-read'])) {
                return true;
            }
            return false;
        });
    }
}
