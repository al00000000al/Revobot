<?php

namespace Revobot\Commands;



class TranslateCmd extends BaseCmd
{

    /**
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /translate фраза');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (!empty($this->input)) {
            $tr = new GoogleTranslate('ru');
            return (string) $tr->translate($this->input);
        }
        return $this->description;
    }
}
