<?php

namespace Revobot;

interface RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri);
}
