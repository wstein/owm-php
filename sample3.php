<?php
$OpenWeather = ['api_key' => 'eeeed3fcd993153a39d003d1b656e8a3'];
$zip = "98109";
$base_url = "http://api.openweathermap.org/data/2.5";
$weather_url = "/weather?zip=" . $zip;
$api_key = "&appid={$OpenWeather['api_key']}";
$api_url = $base_url . $weather_url . $api_key;
$weather = json_decode(file_get_contents($api_url));
print_r($weather);
