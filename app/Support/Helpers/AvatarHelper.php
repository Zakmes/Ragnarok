<?php

if (! function_exists('avatar')) {
    function avatar(string $name): string
    {
        return Avatar::create($name)->toGravatar();
    }
}
