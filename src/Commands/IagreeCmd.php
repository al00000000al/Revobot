<?php

namespace Revobot\Commands;

use Revobot\Util\PMC;

class IagreeCmd extends BaseCmd
{
    const KEYS = ['iagree', 'i_agree'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = '';
    const IS_HIDDEN = true;

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/iagree ');
    }

    public function exec(): string
    {
        PMC::set(provider() . '_agreement' . userId(), 1);
        return "Спасибо за принятие условий пользования! Теперь вы можете использовать все доступные команды нашего бота. Если у вас возникнут вопросы, воспользуйтесь командой /help.";
    }
}
