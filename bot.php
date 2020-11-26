<?php
define("token" , "1458952797:AAEM7IyTMWsYJKO-r_NnM_8G8zUUAMlx5Gc");
include_once "lib/Telegram.php";

$telegram = new Telegram(token,false);

$chat_id    = $telegram->ChatID();
$message_id = $telegram->MessageID();
$text       = $telegram->Text();
$username   = $telegram->Username();

$parameter = [
    'chat_id' => $chat_id,
    'text'    => $username
];

$telegram->sendMessage($parameter);
