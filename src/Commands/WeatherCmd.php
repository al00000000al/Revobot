<?php
/*
 * Get weather by input city name from api.openweathermap.org
 */

namespace Revobot\Commands;


use Revobot\Revobot;
use Revobot\Util\Curl;

class WeatherCmd extends BaseCmd
{
    const KEYS = ['weather','погода','pogoda'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Моя погода';

    private Revobot $bot;
    private const PMC_USER_CITY_NAME = 'pmc_user_city_'; //.$provider_id.$user_id

    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("/weather город");
    }


    public function exec(): string
    {
        if (empty($this->input)) {
            $city_response = $this->bot->pmc->get($this->getUserKey());
            if (!$city_response) {
                return $this->description;
            } else {
                $city = (string)$city_response;
            }
        } else {
            $city = $this->input;
            $this->bot->pmc->set($this->getUserKey(), $city);
        }

        $weather = $this->getWeather($city);

        return $this->getWeatherText($weather);
    }

    private function getUserKey(): string
    {
        return self::PMC_USER_CITY_NAME . $this->bot->provider . $this->bot->getUserId();
    }

    private function getWeather(string $city)
    {
        return json_decode(Curl::get('https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&units=metric&lang=ru&appid=' . OPEN_WEATHER_MAP_API_KEY), true);
    }

    private function getWeatherText($weather): string
    {
        $text = "Погода в {$weather['name']}:\n";
        $text .= "Температура: {$weather['main']['temp']}°C\n";
        $text .= "Давление: {$weather['main']['pressure']}hPa\n";
        $text .= "Влажность: {$weather['main']['humidity']}%\n";
        $text .= "Ветер: {$weather['wind']['speed']}м/с";
        return $text;
    }

}
