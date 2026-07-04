<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

        Response::macro('toast', function (string $message, string $type = 'success', array $events = []) {
            $trigger = array_merge(['toast' => compact('message', 'type')], $events);

            return response('')->withHeaders(['HX-Trigger' => json_encode($trigger)]);
        });
    }
}
