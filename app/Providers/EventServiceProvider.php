<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;

use Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Eloquent のすべてのクエリをダンプするイベントを受信する 
        Event::listen(
            QueryExecuted::class,
            function (QueryExecuted $event) {
                $sql = str_replace(array('%', '?'), array('%%', "'%s'"), $event->sql);
                $fullSql = vsprintf($sql, $event->bindings);

                Log::debug($fullSql);

                /*
                // クエリだけ別にしたい場合
                file_put_contents(
                    storage_path() . DIRECTORY_SEPARATOR . 'logs'
                    . DIRECTORY_SEPARATOR . 'sql_log.sql',
                    $fullSql . ";\n",
                    FILE_APPEND
                );*/
            }
        );
    }
}
