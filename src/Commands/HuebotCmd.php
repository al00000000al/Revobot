<?php

namespace Revobot\Commands;

use Revobot\Util\Strings;

class HuebotCmd extends BaseCmd
{
    const KEYS = ['huebot', 'хуеслово', 'slovo', 'hueslovo'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'напишите слово и получите х*еслово';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/slovo напишите слово и получите х*еслово');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        return $this->process($this->input);
    }

    private function process(string $input): string
    {
        $words = Strings::stringToWords($input);
        $response = [];
        foreach ($words as $word) {
            $parts = Strings::splitIntoSyllables($word);
            if (count($parts) > 1) {
                if (mb_substr($parts[0], -1) === 'и') {
                    $pref = 'и';
                } else  if (mb_substr($parts[0], -1) === 'а') {
                    $pref = 'я';
                } else  if (mb_substr($parts[0], -1) === 'ю') {
                    $pref = 'ю';
                } else  if (mb_substr($parts[0], -1) === 'о') {
                    $pref = 'ё';
                } else {
                    $pref = 'е';
                }
                $response[] = 'ху' . $pref . (string)array_pop($parts);
            } else {
                if (Strings::isVowelLetter($parts[0], 0)) {
                    $response[] = 'хуй' . mb_substr($parts[0], 1);
                } else {
                    $response[] = 'хуй' . $parts[0];
                }
            }
        }

        return implode(' ', $response);
    }
}
