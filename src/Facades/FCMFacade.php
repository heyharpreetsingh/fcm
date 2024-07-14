<?php

namespace Heyharpreetsingh\FCM\Facades;

use Illuminate\Support\Facades\Facade;

class FCMFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hhs.fcm';
    }
}
