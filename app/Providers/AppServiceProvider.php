<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // مشاركة إشعارات المستخدم مع لوحة التحكم (للنافبار والمودال)
        View::composer('layouts.dashboard.app', function ($view) {
            $user = auth()->user();
            if (!$user) {
                return;
            }
            $view->with([
                'headerNotifications' => $user->unreadNotifications()->take(10)->get(),
                'unreadNotificationsCount' => $user->unreadNotifications()->count(),
            ]);
        });
    }
}
