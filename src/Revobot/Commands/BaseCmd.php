<?php

namespace Revobot\Commands;

class BaseCmd
{
    protected string $description = 'Base cmd';
    protected string $input;



    public function __construct($input){
        $this->input = trim($input);

    }

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

}