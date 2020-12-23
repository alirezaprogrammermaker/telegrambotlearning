<?php
define("token" , "bot_key");
include_once "lib/Telegram.php";
include_once "lib/database.php";

define("USERS"      , "users");
define("PRODUCTS"   , "products");
define("CATEGORIES" , "catgories");

///
///
/// sessions
define("session_free"             , 0); // کاربر هنوز هیچ دستوری نداده

define("session_create_product"   , 2);

define("session_name_category"    , 3);
define("session_name_product"     , 4);

define("session_price_product"    , 5);
define("session_category_product" , 6);


$telegram = new Telegram(token,false);


$chat_id       = $telegram->ChatID();
$message_id    = $telegram->MessageID();
$text          = $telegram->Text();
$username      = $telegram->Username();
$callback_data = $telegram->Callback_Data();

$type          = $telegram->getUpdateType();


$signup     = "شما عضو بودید";
$not_signup = "شما عضو شدید";

$create_cat = "c_cat";
$create_prod = "c_prod";
$charge = 0;

$check_user = $db->query("select * from `".USERS."` where  chat_id = '{$chat_id}'")->fetch();
if ($check_user != false){
    $charge  = $check_user['charge'];
    $session = $check_user['session'];
}else{
    $db->query("INSERT INTO `".USERS."`( `chat_id`) VALUES ('{$chat_id}')");
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

    case "message":

        switch ($session){
            case session_free:
                if ($text == "/charge"){
                    $payam = "your account charge : {$charge}";
                    $telegram->sendMessage(['chat_id' => $chat_id , 'text' => $payam]);
                }
                if ($text == "/inline"){
                    $keyboard = [];
                    $keyboard[] = [$telegram->buildInlineKeyboardButton("ایجاد دسته بندی", "", $create_cat)];
                    $keyboard[] = [$telegram->buildInlineKeyboardButton("ایجاد محصول", "", $create_prod)];
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

                break;
            case session_name_category:
                create_category($text);
                $msg = "دسته ای با نام " . $text . " ایجاد شد.";
                send_message($msg);
                update_session(session_free);
                break;
            case session_name_product:

                break;

        }
        break;
    case "callback_query":

        if ($callback_data == $create_prod ){
            switch ($session){
                case session_free:
                    send_message("ننام محصول خود را ارسال کنید : ");
                    update_session(session_name_product);
                    break;

            }
        }elseif ($callback_data == $create_cat){

                switch ($session){
                    case session_free:
                        send_message("نام دسته خود را ارسال کنید : ");
                        update_session(session_name_category);
                        break;
                }

        }
        break;

}

function create_category($name,$c_or_p = "category"){
    global $db;
    if ($c_or_p == "category"){
        $sql = "INSERT INTO `catgories` (`name`) VALUES ('$name')";
        $db->query($sql);
    }
    if ($c_or_p == "product"){
        $sql = "INSERT INTO `products` (`name`) VALUES ('" . $name . "')";
        $db->exec($sql);
        return $db->lastInsertId();
    }

}

function update_session($session)
{
    global $db,$chat_id;
    $sql = "UPDATE `".USERS."` SET `session` = '" . $session . " ' WHERE `users`.`chat_id` = '" . $chat_id . "'";
    $db->query($sql);
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
