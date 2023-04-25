<?php

namespace App\Providers;

use App\Models\ApplicationChange;
use App\Models\User;
use App\Models\UserPreviousService;
use App\Models\UserRelative;
use App\Observers\ApplicationChangeObserver;
use App\Observers\UserObserver;
use App\Observers\UserPreviousServiceObserver;
use App\Observers\UserRelativeObserver;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') !== 'local') {
            $url->forceScheme('https');
        }

        Validator::extend('iunique', function ($attribute, $value, $parameters, $validator) {
            $query = DB::table($parameters[0]);
            $column = $query->getGrammar()->wrap($parameters[1]);
            if (isset($parameters[2])) {
                $query = $query->where('id', '<>', $parameters[2]);
            }
            return ! $query->whereRaw("lower({$column}) = lower(?)", [$value])->count();
        });
    }
}
