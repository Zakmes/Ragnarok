<?php

use App\Domains\Users\Enums\GroupEnum;

return [

    /**
     * -------------------------------------------------------------------------
     * Kiosk prefix
     * -------------------------------------------------------------------------
     *
     * Here you can define the prefix for your kiosk dashboard routes
     * in your application.
     *
     */

    'kiosk_prefix' => 'kiosk',

    /**
     * -------------------------------------------------------------------------
     * Authentication configuration
     * -------------------------------------------------------------------------
     *
     * Here you can define all the settings for your authentication scaffolding.
     *
     */

    'auth' => [
        'password_length' => 12,
    ],

    /**
     * -------------------------------------------------------------------------
     * Access Configuration
     * -------------------------------------------------------------------------
     *
     * Here you can define the access roles  for specific parts of your application.
     *
     */

    'access' => [
        'superAdmin' => GroupEnum::WEBMASTER,
        'kiosk' => [GroupEnum::WEBMASTER, GroupEnum::DEVELOPER],
    ],

    /**
     * -------------------------------------------------------------------------
     * Application modules configuration
     * -------------------------------------------------------------------------
     *
     * Here can u define which modules you will be using in your application.
     * Like: 2fa, API tokens, etc...
     */

    'modules' => [
        '2fa' => true,
        'api-tokens' => true,
        'announcements' => true,
    ],

];
