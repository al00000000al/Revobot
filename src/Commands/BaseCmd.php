<?php

namespace Revobot\Commands;

class BaseCmd
{
    protected string $description = 'Base cmd';

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    protected string $input;


    /**
     * @param string $input
     */
    public function __construct(string $input){
        $this->input = trim($input);

    }

    /**
     * @return string
     */
    public function exec(): string
    {
      return $this->input;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }



    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @param int $user
     * @return bool
     */
    public function isAdmin(int $user): bool
    {
        return in_array($user, TG_BOT_ADMINS, true);
    }
}