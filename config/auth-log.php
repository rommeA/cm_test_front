<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Device Number
    |--------------------------------------------------------------------------
    |
    | Here you determine the number of devices from which new logins
    | will not be considered suspicious.
    |
    */

    'devices_count' => env('AUTH_LOG_DEVICES_COUNT', 2),

    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    |
    | Email address to be used to notify Admin about blocked accounts.
    |
    */

    'admin_email' => env('AUTH_LOG_ADMIN_EMAIL', 'romme@femco.ru'),

];
