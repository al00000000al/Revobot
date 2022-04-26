<?php

namespace Revobot\Money;

/**
 * @kphp-immutable-class *
 *
 * This instances will be stored in shared memory
 */
class StatCached {

    /** @var float[] $users */
    public array $users;

    /** @var string[] $usernames */
    public array $usernames;

    /**
     * @param float[] $users
     */
    public function __construct(array $users, array $usernames)
    {
        $this->users = $users;
        $this->usernames = $usernames;
    }
}

