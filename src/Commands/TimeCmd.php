<?php

namespace Revobot\Commands;

class TimeCmd extends BaseCmd
{
    public function exec(): string
    {
        return date('Y-m-d H:i:s');
    }
}