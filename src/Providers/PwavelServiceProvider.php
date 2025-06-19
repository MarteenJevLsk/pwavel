<?php

namespace Marteen\Pwavel\Providers;

use Illuminate\Support\ServiceProvider;
use Marteen\Pwavel\Commands\PublishPwaWorker;

class PwavelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            PublishPwaWorker::class,
        ]);
    }

    public function boot()
    {
        
    }
}
