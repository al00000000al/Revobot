<?php

namespace Revobot\Commands;

use Revobot\Services\Tmdb;
use Revobot\Util\PMC;

class FilmCmd extends BaseCmd
{
    const KEYS = ['film'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'get random film';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/film get random film');
    }

    public function exec(): string
    {
        $latest_id = PMC::get('tmdb_last_id');
        if (!$latest_id) {
            $latest_response = (array)Tmdb::latest();
            $latest_id = (int) $latest_response['id'];
            PMC::set('tmdb_last_id', $latest_id, 60 * 60 * 24);
        }
        $film = (array)self::getFilm($latest_id);
        if (!$film) {
            return "Ничего не нашли";
        }
        $title = (string)$film['title'] ?? '';
        $release_date = (string)$film['release_date'] ?? '';
        $overview = (string)$film['overview'] ?? '';
        return "Cлучайный фильм:
{$title}
Год: {$release_date}
Описание: {$overview}";
    }

    private function getFilm($latest_id):array
    {
        for ($i = 0; $i <= 5; $i++) {
            $film = Tmdb::geetById(mt_rand(1, (int)$latest_id));
            if ($film['title'])  {
                return $film;
            }
        }
        return [];
    }
}
