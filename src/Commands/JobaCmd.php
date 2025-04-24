<?php

namespace Revobot\Commands;

use Revobot\JobWorkers\JobLauncher;
use Revobot\JobWorkers\Requests\Test;

class JobaCmd extends BaseCmd
{
    const KEYS = ['joba'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'run simple kphp jobworker test';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/joba run simple kphp jobworker test');
    }

    public function exec(): string
    {
        if (!JobLauncher::isEnabled()) {
            return "жобы не включены";
        }
        $job_request = new Test($this->input, chatId());
        JobLauncher::startNoReply($job_request, 10.0);
        return 'Started job';
    }
}
