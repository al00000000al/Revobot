<?php

namespace Revobot\Commands\Key;

use Revobot\Commands\BaseCmd;
use Revobot\Revobot;

class KeyEditCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        self::setDescription('Введите /key.edit <ключ> <значение>');
    }

    public function exec(): string
    {
        if(!self::isAdmin($this->bot->getUserId())){
            return '';
        }

        if(!empty($this->input)) {
            $params = explode(' ', $this->input);
            if(count($params) > 1) {
                $key = $params[0];
                $value = substr($this->input, strlen($key));

                self::setKey($this->input, $value);
                return 'Ключ '.$key.'='.$value;
            }else{
                return $this->description;
            }
        }
        return $this->description;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    private function setKey(string $key, $value): bool
    {
        return $this->bot->pmc->set($key, $value);
    }


}