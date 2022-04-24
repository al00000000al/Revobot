<?php

namespace Revobot\Util;

class Hash
{
    public static function generate(...$args)
    {
        return hash('sha256', implode(':', $args) . SECRET_KEY);
    }
}