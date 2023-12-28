<?php
/*
 * Get weather by input city name from api.openweathermap.org
 */

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Util\Curl;
use Revobot\Util\PMC;

class WeatherCmd extends BaseCmd
{
    const KEYS = ['weather', 'погода', 'pogoda'];
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
            $city_response = PMC::get($this->getUserKey());
            if (!$city_response) {
                return $this->description;
            } else {
                $city = (string)$city_response;
            }
        } else {
            $city = $this->input;
            PMC::set($this->getUserKey(), $city);
        }

        $weather = $this->getWeather($city);

        return $this->getWeatherText($weather);
    }

    private function getUserKey(): string
    {
        return self::PMC_USER_CITY_NAME . $this->bot->provider . userId();
    }

    private function getWeather(string $city)
    {
        return json_decode(Curl::get('https://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&units=metric&lang=ru&appid=' . Config::get('open_weather_map_api_key')), true);
    }

    private function getWeatherText($weather): string
    {
        if ($weather['cod'] == 404) {
            return "Такого города нет";
        }
        return <<<TEXT
Погода в {$weather['name']}:
Температура: {$weather['main']['temp']}°C
Ощущается как: {$weather['main']['feels_like']}°C
Давление: {$weather['main']['pressure']}hPa
Влажность: {$weather['main']['humidity']}%
Ветер: {$weather['wind']['speed']}м/с

{$weather['weather']['description']}
TEXT;
    }
}
