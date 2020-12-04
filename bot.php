<?php
define("token" , "1458952797:AAGctLE-d63iTeJnSs3wXWsC3MmcDEi5YTk");
include_once "lib/Telegram.php";
include_once "lib/database.php";

$telegram = new Telegram(token,false);


$chat_id       = $telegram->ChatID();
$message_id    = $telegram->MessageID();
$text          = $telegram->Text();
$username      = $telegram->Username();
$callback_data = $telegram->Callback_Data();

$type          = $telegram->getUpdateType();




$signup     = "شما عضو بودید";
$not_signup = "شما عضو شدید";
$charge = 0;

$check_user = $db->query("select * from `users` where  chat_id = '{$chat_id}'")->fetch();
if ($check_user != false){
    $charge = $check_user['charge'];
}else{
    $db->query("INSERT INTO `users`( `chat_id`) VALUES ('{$chat_id}')");
    $telegram->sendMessage(['chat_id' => $chat_id , 'text' => $not_signup]);

}
if ($callback_data <> "" and is_numeric($callback_data)){
    $product = get_product($callback_data);
    $text = "نام"     . " : "  . $product['name']   ."\n";
    $text .= "توضیحات" . " : "  . $product['desc']   ."\n";
    $text .= "قیمت"  . " : "   . $product['price'] ." تومان"."\n";
    $keyboard = [];
    $keyboard[] =[$telegram->buildInlineKeyboardButton("خرید این محصول" ,"","buy=".$product['id'])];
    $inline_keyboard =$telegram->buildInlineKeyBoard($keyboard);
    $telegram->sendMessage([
        "text"         => $text,
        "chat_id"      => $chat_id,
        "reply_markup" => $inline_keyboard
    ]);
}

@$callback_data_explode = explode("=" ,$callback_data);
if ($callback_data_explode[0] == "buy"){
    $product = get_product();
    $price = $product['price'];
    if ($price <= $charge){
        send_message("محصول خریداری شد");
    }else{
        send_message("اعتبار شما کافی نیست");
    }
}
if ($text == "/charge"){
    $payam = "your account charge : {$charge}";
    $telegram->sendMessage(['chat_id' => $chat_id , 'text' => $payam]);
}

if ($text == "/inline"){
    $keyboard = [];
    $keyboard[] =[$telegram->buildInlineKeyboardButton("دکمه 1" ,"","")];
    $inline_keyboard =$telegram->buildInlineKeyBoard($keyboard);
    $telegram->sendMessage([
        "text"         => "منو ربات",
        "chat_id"      => $chat_id,
        "reply_markup" => $inline_keyboard
    ]);
}
if ($text == "/product"){
    $products = get_product();
    if ($products != false){
        $keyboard = [];
        foreach ($products as $product){
            $keyboard[] =[$telegram->buildInlineKeyboardButton($product['name'],"",$product['id']) ];

        }
        $inline_keyboard =$telegram->buildInlineKeyBoard($keyboard);
        $telegram->sendMessage([
            "text"         => "محصولات ما",
            "chat_id"      => $chat_id,
            "reply_markup" => $inline_keyboard
        ]);

    }else{
        send_message("محصولی موجود نیست");
    }
}


switch ($type){

    case "photo":
        $file_name = rand(1,99999).".jpg";
        $file_id = $telegram->smallPhotoFileID();
        $file    = $telegram->getFile($file_id)['result']['file_path'];
        $telegram->downloadFile($file,$file_name);
        $telegram->sendPhoto(
            [
            "chat_id" => $chat_id,
            "photo"   => new CURLFile(realpath($file_name)),
            "caption" => $file_name
        ]);
        break;
    case "audio":
        $file_name = rand(1,99999).".mp3";
        $file_id = $telegram->audioFileID();
        $file    = $telegram->getFile($file_id)['result']['file_path'];
        $telegram->downloadFile($file,$file_name);
        $telegram->sendAudio(
            [
                "chat_id" => $chat_id,
                "audio"   => new CURLFile(realpath($file_name)),
                "caption" => $file_name
            ]
        );

        break;
    case "video":
        $file_name = rand(1,99999).".mp4";
        $file_id = $telegram->videoFileID();
        $file    = $telegram->getFile($file_id)['result']['file_path'];
        $telegram->downloadFile($file,$file_name);
        $telegram->sendVideo(
            [
                "chat_id" => $chat_id,
                "video"   => new CURLFile(realpath($file_name)),
                "caption" => $file_name
            ]
        );

        break;

}

function send_message($text){
    global  $telegram,$chat_id ;
    $data = ["chat_id" => $chat_id ,"text" => $text];
    $telegram->sendMessage($data);
}
function get_product($id = ''){
    global $db;
    if ($id == ""){
        $products = $db->query("select * from products")->fetchAll();
        if (count($products) > 0){
            return $products;
        }else{
            return false;
        }
    }else{
        $product = $db->query("select * from products")->fetch();
        if ($product != false){
            return $product;
        }else{
            return false;
        }
    }
}
