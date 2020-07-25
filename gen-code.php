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


$response = Unirest\Request::get(
    'https://api.openweathermap.org/data/2.5/weather',
    ['Accept' => 'application/json'],
    ['q' => $city, 'units' => 'metric', 'appid' => $api_key]
);

# result

$code = $response->code;            // HTTP Status code
$headers = $response->headers;      // Headers
$data = $response->body;            // Parsed body
$raw_body = $response->raw_body;    // Unparsed body


$php_java_mapping = [
    "boolean" => "Boolean",
    "integer" => "Int",
    "double"  => "Float",
    "string" => "String",
    "array" => "JSONArray",
    "object" => "JSONObject",
    "resource"  => "Resource",
    "NULL" => "Null",
    "unknown type"  => "Unknown"
];

function walk_data_struct($data_struct, $head, $object_format, $array_format, $type_mapping, $fun, $key = null)
{
    if (is_object($data_struct)) {
        $vars = get_object_vars($data_struct);
        foreach ($vars as $key => $value) {
            $type = gettype($value);
            $getter = str_replace('{}', $key, $object_format);
            if (isset($type_mapping)) {
                $getter = str_replace('<>', $type_mapping[$type], $getter);
            }
            walk_data_struct($value, $head . $getter, $object_format, $array_format, $type_mapping, $fun, $key);
        }
    } elseif (is_array($data_struct)) {
        for ($i = 0; $i < sizeof($data_struct); $i++) {
            $value = $data_struct[$i];
            $type = gettype($value);
            $getter = str_replace('{}', $i, $array_format);
            if (isset($type_mapping)) {
                $getter = str_replace('<>', $type_mapping[$type], $getter);
            }
            walk_data_struct($value, $head . $getter, $object_format, $array_format, $type_mapping, $fun, $key);
        }
    } else {
        $value = $data_struct;
        $fun($head, $value, $key);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Wetter in <?= $data->name ?></title>
</head>

<body>
    <code>
        <? walk_data_struct($data, '$data', '->{}', '[{}]', null, function($path, $value, $key) { ?>
        <?= $key ?>: &lt;?= <?= $path ?> ?&gt;&lt;br&gt;<br>
        <? }) ?>
    </code>
    <br>
    <code>
        <? walk_data_struct($data, 'data', '.get<>("{}")', '.get<>({})', $php_java_mapping, function($path, $value, $key) { ?>
        var <?= $key ?> = <?= $path ?>;<br>
        <? }) ?>
    </code>
</body>

</html>