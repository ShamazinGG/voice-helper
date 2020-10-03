<?php

require 'vendor/autoload.php';
require 'weather.php';
$message = mb_strtolower($_REQUEST['text']);
$get_audio = $_REQUEST['get_audio'];
$responses = [];
if (mb_substr_count($message, "привет")) {
    $responses [] = "Здравствуйте";
}

if (mb_substr_count($message, "как дела")) {
    $responses [] = "Пойдёт";
}
if (mb_substr_count($message, "что делаешь")) {
    $responses [] = "Отвечаю на вопросы";
}
if (mb_substr_count($message, "сколько времени")) {
    $time = date("H:i:s");
    $responses [] = "Текущее время: $time;";
}
if (mb_substr_count($message, "какой сегодня день")) {
    $time = date("d.m.Y");
    $responses [] = "Сегодня: $time;";
}
//weather
if (preg_match("/какая погода в городе (\w+)/iu", $message, $matches)) {
    $city = urlencode($matches[1]);
    [$temp_c, $condition] = getWeather($city);
    $responses [] = "там сейчас $condition, где-то $temp_c градусов";
}
if ($responses) {
    $response = join(',', $responses);
} else {
    $response = "окей";
}

$result = join(".", $responses);
if ($get_audio) {
    $provider = new \duncan3dc\Speaker\Providers\ResponsiveVoiceProvider("ru-RU");
    $tts = new \duncan3dc\Speaker\TextToSpeech($result, $provider);
    header("Content-Type: audio/mpeg");
    echo $tts->getAudioData();
    //sound
} else {
    echo $result;
}

