<?php
require_once dirname(__FILE__) . '/Unirest.php'; # import Unirest lib

$api_key = 'eeeed3fcd993153a39d003d1b656e8a3';


# get city from url (or default Berlin)

if (isset($_GET['city'])) {
    $city = $_GET['city'];
} else {
    $city = "Berlin";
}

# Unirest request

$headers = array('Accept' => 'application/json');
$query = array('q' => $city, 'units' => 'metrics', 'appid' => $api_key);

$response = Unirest\Request::get('http://api.openweathermap.org/data/2.5/weather', $headers, $query);

# result

$code = $response->code;        // HTTP Status code
$headers = $response->headers;     // Headers
$data = $response->body;        // Parsed body
$raw_body = $response->raw_body;    // Unparsed body

?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Wetter in <?= $data->name ?></title>
</head>

<body>
    <div style="font-size: medium; font-weight: bold; margin-bottom: 0px;"><?= $data->name ?></div>
    <div style="float: left; width: 130px;">
        <div style="display: block; clear: left;">
            <div style="float: left;" title="broken clouds">
                <img height="45" width="45" style="border: medium none; width: 45px; height: 45px; background: url(&quot;http://openweathermap.org/img/w/04d.png&quot;) repeat scroll 0% 0% transparent;" src="http://openweathermap.org/images/transparent.png" />
            </div>
            <div style="float: left;">
                <div style="display: block; clear: left; font-size: medium; font-weight: bold; padding: 0pt 3pt;" title="Current Temperature">19.39°C</div>
                <div style="display: block; width: 85px; overflow: visible;"></div>
            </div>
        </div>
        <div style="display: block; clear: left; font-size: small;">Clouds: 75%</div>
        <div style="display: block; clear: left; color: gray; font-size: x-small;">Humidity: 38%</div>
        <div style="display: block; clear: left; color: gray; font-size: x-small;">Wind: 2.6 m/s</div>
        <div style="display: block; clear: left; color: gray; font-size: x-small;">Pressure: 1014hpa</div>
    </div>
    <div style="display: block; clear: left; color: gray; font-size: x-small;">
        <a href="http://openweathermap.org/city/2903889" target="_blank">More..</a>
    </div>
</body>

</html>