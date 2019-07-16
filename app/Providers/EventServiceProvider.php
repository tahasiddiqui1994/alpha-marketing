<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Message;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\MessageCreated' => [
            'App\Listeners\SendMessageCreatedNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    private $activitiesToRecord = ['created','updated','destroyed'];

    public function boot()
    {
        parent::boot();
    }
}
