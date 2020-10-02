<?php

if (! function_exists('kioskRoute')) {
    /**
     * Helper for setting kiosk specific routes in the application.
     *
     * @param  string       $name   The route name for the kiosk.
     * @param  array|object $params The parameters or model object
     * @return string
     */
    function kioskRoute(string $name, $params = []): string
    {
        return route(config('spoon.kiosk_prefix') . '.' . $name, $params);
    }
}

if (! function_exists('kioskActive')) {
    /**
     * Determine whether the given kiosk route is active or not.
     *
     * @param  string $name  The current name for the given route
     * @param  string $class The custom css class for indicating that the route is active.
     * @return mixed
     */
    function kioskActive(string $name, string $class = 'active')
    {
        return active(config('spoon.kiosk_prefix') . '.' . $name, $class);
    }
}
