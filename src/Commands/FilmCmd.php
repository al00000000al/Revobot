<?php

namespace Revobot\Commands;

use Revobot\Services\Providers\Tg;
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
        $id = (string)$film['id'] ?? '';
        $title = (string)$film['title'] ?? '';
        $release_date = (string)$film['release_date'] ?? '';
        $overview = (string)$film['overview'] ?? '';
        $poster = (string)$film['poster_path'] ?? '';
        $rating = (string)$film['vote_average'] ?? '0';
        $genres = implode(', ', array_column($film['genres'], 'name'));

        Tg::sendPhoto(chatId(), Tmdb::IMAGE_HOST . $poster, "{$title} ({$release_date})
-----------------------
{$overview}
-----------------------
Rating: {$rating}
Genres: {$genres}
https://www.themoviedb.org/movie/{$id}");

        return '';
    }

    private function getFilm($latest_id):array
    {
        for ($i = 0; $i <= 5; $i++) {
            $film = Tmdb::geetById(mt_rand(1, (int)$latest_id));

            if ($film['poster_path'] && $film['release_date']
            && $film['title'] && $film['overview']
            && $film['vote_average'] && (float)$film['vote_average'] > 0) {
                return $film;
            }
        }
        return [];
    }
}
