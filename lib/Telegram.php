<?php

if (file_exists('TelegramErrorLogger.php')) {
    require_once 'TelegramErrorLogger.php';
}

/**
 * Telegram Bot Class.
 *
 * @author Gabriele Grillo <gabry.grillo@alice.it>
 */
class Telegram
{
    /**
     * Constant for type Inline Query.
     */
    const INLINE_QUERY = 'inline_query';
    /**
     * Constant for type Callback Query.
     */
    const CALLBACK_QUERY = 'callback_query';
    /**
     * Constant for type Edited Message.
     */
    const EDITED_MESSAGE = 'edited_message';
	/**
     * Constant for type Edited Channel Post Message.
     */
    const EDITED_CHANNEL_POST = 'edited_channel_post';
    /**
     * Constant for type Reply.
     */
    const REPLY = 'reply';
    /**
     * Constant for type Message.
     */
    const MESSAGE = 'message';
    /**
     * Constant for type Forwarded Message.
     */
    const FORWARDED = 'forwarded';	
    /**
     * Constant for type Photo.
     */
    const PHOTO = 'photo';
    /**
     * Constant for type Video.
     */
    const VIDEO = 'video';
    /**
     * Constant for type Audio.
     */
    const AUDIO = 'audio';
    /**
     * Constant for type Voice.
     */
    const VOICE = 'voice';
    /**
     * Constant for type Document.
     */
    const DOCUMENT = 'document';
    /**
     * Constant for type animation.
     */
    const ANIMATION = 'animation';
    /**
     * Constant for type sticker.
     */
    const STICKER = 'sticker';	
    /**
     * Constant for type Location.
     */
    const LOCATION = 'location';
    /**
     * Constant for type Contact.
     */
    const CONTACT = 'contact';
    /**
     * Constant for type Dice.
     */
    const DICE = 'dice';    
    /**
     * Constant for type New_Chat_Member.
     */
    const NEW_CHAT_MEMBER = 'new_chat_member';
	/**
     * Constant for type Left_Chat_Member.
     */
    const LEFT_CHAT_MEMBER = 'left_chat_member';	
    /**
     * Constant for type Channel Post.
     */
    const CHANNEL_POST = 'channel_post';

    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
	private $proxy;

    /// Class constructor

    /**
     * Create a Telegram instance from the bot token
     * \param $bot_token the bot token
	 * \param $log_errors enable or disable the logging
	 * \param $proxy array with the proxy configuration (url, port, type, auth)
     * \return an instance of the class.
     */
    public function __construct($bot_token, $log_errors = true, array $proxy=array())
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
		$this->proxy = $proxy;
    }

    /// Do requests to Telegram Bot API

    /**
     * Contacts the various API's endpoints
     * \param $api the API endpoint
     * \param $content the request parameters as array
     * \param $post boolean tells if $content needs to be sends
     * \return the JSON Telegram's reply.
     */
    public function endpoint($api, array $content, $post = true)
    {
        $url = 'https://api.telegram.org/bot'.$this->bot_token.'/'.$api;
        if ($post) {
            $reply = $this->sendAPIRequest($url, $content);
        } else {
            $reply = $this->sendAPIRequest($url, [], false);
        }

        return json_decode($reply, true);
    }

    /// A method for testing your bot.

    /**
     * A simple method for testing your bot's auth token. Requires no parameters.
     * Returns basic information about the bot in form of a User object.
     * \return the JSON Telegram's reply.
     */
    public function getMe()
    {
        return $this->endpoint('getMe', [], false);
    }

    /// A method for responding http to Telegram.

    /**
     * \return the HTTP 200 to Telegram.
     */
    public function respondSuccess()
    {
        http_response_code(200);

        return json_encode(['status' => 'success']);
    }

    /// set commands

    public function BotCommand($command, $description)
    {
        return array("command" =>$command ,"description" => $description);
    }

    public function setMyCommands(array $content)
    {
        return $this->endpoint('setMyCommands', ["commands" =>json_encode($content)], true);
    }
    
    /// Send a message

    public function sendMessage(array $content)
    {
        return $this->endpoint('sendMessage', $content);
    }
    
    // Send Dice
	public function sendDice(array $content)
    {
        return $this->endpoint('sendDice', $content);
    }
    /// Send a Poll

    public function sendPoll(array $content)
    {
        return $this->endpoint('sendPoll', $content);
    }	

    /// Forward a message

    public function forwardMessage(array $content)
    {
        return $this->endpoint('forwardMessage', $content);
    }

    /// Send a photo

    public function sendPhoto(array $content)
    {
        return $this->endpoint('sendPhoto', $content);
    }

    /// Send an audio

    public function sendAudio(array $content)
    {
        return $this->endpoint('sendAudio', $content);
    }

    /// Send a document

    public function sendDocument(array $content)
    {
        return $this->endpoint('sendDocument', $content);
    }

    /// Send a sticker

    public function sendSticker(array $content)
    {
        return $this->endpoint('sendSticker', $content);
    }

    /// Send a video

    public function sendVideo(array $content)
    {
        return $this->endpoint('sendVideo', $content);
    }

    /// Send a voice message

    public function sendVoice(array $content)
    {
        return $this->endpoint('sendVoice', $content);
    }

    /// Send a location

    public function sendLocation(array $content)
    {
        return $this->endpoint('sendLocation', $content);
    }

    /// Edit Message Live Location

    public function editMessageLiveLocation(array $content)
    {
        return $this->endpoint('editMessageLiveLocation', $content);
    }

    /// Stop Message Live Location

    public function stopMessageLiveLocation(array $content)
    {
        return $this->endpoint('stopMessageLiveLocation', $content);
    }

    /// Set Chat Sticker Set

    public function setChatStickerSet(array $content)
    {
        return $this->endpoint('setChatStickerSet', $content);
    }

    /// Delete Chat Sticker Set

    public function deleteChatStickerSet(array $content)
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }

    /// Send Media Group

    public function sendMediaGroup(array $content)
    {
        return $this->endpoint('sendMediaGroup', $content);
    }

    /// Send Venue

    public function sendVenue(array $content)
    {
        return $this->endpoint('sendVenue', $content);
    }

    //Send contact

    public function sendContact(array $content)
    {
        return $this->endpoint('sendContact', $content);
    }

    /// Send a chat action

    public function sendChatAction(array $content)
    {
        return $this->endpoint('sendChatAction', $content);
    }

    /// Get a list of profile pictures for a user

    public function getUserProfilePhotos(array $content)
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }

    /// Use this method to get basic info about a file and prepare it for downloading

    /**
     *  Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
     * \param $file_id String File identifier to get info about
     * \return the JSON Telegram's reply.
     */
    public function getFile($file_id)
    {
        $content = ['file_id' => $file_id];

        return $this->endpoint('getFile', $content);
    }

    /// Kick Chat Member

    public function kickChatMember(array $content)
    {
        return $this->endpoint('kickChatMember', $content);
    }

    /// Leave Chat

    public function leaveChat(array $content)
    {
        return $this->endpoint('leaveChat', $content);
    }

    /// Unban Chat Member

    public function unbanChatMember(array $content)
    {
        return $this->endpoint('unbanChatMember', $content);
    }

    /// Get Chat Information

    public function getChat(array $content)
    {
        return $this->endpoint('getChat', $content);
    }

    /**
     * Use this method to get a list of administrators in a chat. On success, returns an Array of <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> objects that contains information about all chat administrators except */
	
    public function getChatAdministrators(array $content)
    {
        return $this->endpoint('getChatAdministrators', $content);
    }

    /**
     * Use this method to get the number of members in a chat. Returns <em>Int</em> on success.<br/>Values inside $content*/
	
    public function getChatMembersCount(array $content)
    {
        return $this->endpoint('getChatMembersCount', $content);
    }

    /**
     * Use this method to get information about a member of a chat. Returns a <a href="https://core.telegram.org/bots/api#chatmember">ChatMember</a> object on success.<br/>Values inside $content:<br/>*/
	
    public function getChatMember(array $content)
    {
        return $this->endpoint('getChatMember', $content);
    }

    /**
     * Use this method to send answers to an inline query. On success, <em>True</em> is returned.<br>No more than <strong>50</strong> results per query are allowed.<br/>Values inside $content:<br/>
*/
	
    public function answerInlineQuery(array $content)
    {
        return $this->endpoint('answerInlineQuery', $content);
    }

    /// Set Game Score

    public function setGameScore(array $content)
    {
        return $this->endpoint('setGameScore', $content);
    }

    /// Answer a callback Query

    /**
     * Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, <em>True</em> is returned.<br/>Values inside $content:<br/>
     */
	
    public function answerCallbackQuery(array $content)
    {
        return $this->endpoint('answerCallbackQuery', $content);
    }

    /**
     * Use this method to edit text messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>). On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned.<br/>Values inside $content:<br/>
	 */
	
    public function editMessageText(array $content)
    {
        return $this->endpoint('editMessageText', $content);
    }

    /**
     * Use this method to edit captions of messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>). On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned.<br/>Values inside $content:*/
	
    public function editMessageCaption(array $content)
    {
        return $this->endpoint('editMessageCaption', $content);
    }

    /**
     * Use this method to edit only the reply markup of messages sent by the bot or via the bot (for <a href="https://core.telegram.org/bots/api#inline-mode">inline bots</a>).  On success, if edited message is sent by the bot, the edited <a href="https://core.telegram.org/bots/api#message">Message</a> is returned, otherwise <em>True</em> is returned.<br/>Values inside $content:*/
	
    public function editMessageReplyMarkup(array $content)
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }

    /// Use this method to download a file

    /**
     *  Use this method to to download a file from the Telegram servers.
     * \param $telegram_file_path String File path on Telegram servers
     * \param $local_file_path String File path where save the file.
     */
    public function downloadFile($telegram_file_path, $local_file_path)
    {
        $file_url = 'https://api.telegram.org/file/bot'.$this->bot_token.'/'.$telegram_file_path;
        $in = fopen($file_url, 'rb');
        $out = fopen($local_file_path, 'wb');

        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /// Set a WebHook for the bot

    /**
     *  Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts.
     *
     * If you'd like to make sure that the Webhook request comes from Telegram, we recommend using a secret path in the URL, e.g. https://www.example.com/<token>. Since nobody else knows your botâ€˜s token, you can be pretty sure itâ€™s us.
     * \param $url String HTTPS url to send updates to. Use an empty string to remove webhook integration
     * \param $certificate InputFile Upload your public key certificate so that the root certificate in use can be checked
     * \return the JSON Telegram's reply.
     */
    public function setWebhook($url, $certificate = '')
    {
        if ($certificate == '') {
            $requestBody = ['url' => $url];
        } else {
            $requestBody = ['url' => $url, 'certificate' => "@$certificate"];
        }

        return $this->endpoint('setWebhook', $requestBody, true);
    }

    /// Delete the WebHook for the bot

    /**
     *  Use this method to remove webhook integration if you decide to switch back to <a href="https://core.telegram.org/bots/api#getupdates">getUpdates</a>. Returns True on success. Requires no parameters.
     * \return the JSON Telegram's reply.
     */
    public function deleteWebhook()
    {
        return $this->endpoint('deleteWebhook', [], false);
    }

    /// Get the data of the current message

    /** Get the POST request of a user in a Webhook or the message actually processed in a getUpdates() enviroment.
     * \return the JSON users's message.
     */
    public function getData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');

            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    /// Set the data currently used
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /// Get the text of the current message

    /**
     * \return the String users's text.
     */
    public function Text()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['data'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['text'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['text'];
        }

        return @$this->data['message']['text'];
    }
	
	
    /// Get the caption of the current message

    /**
     * \return the String users's text.
     */
    public function Caption()
    {
        return @$this->data['message']['caption'];
    }
	
    /// Get the Entities of the current message

    /**
     * \return the String users's text.
     */
    public function Entities()
    {
        return @$this->data['message']['entities'];
    }
	
    /// Get the replyMarkup array of the current message

    /**
     * \return thearray of reply markup.
     */
    public function ReplyMarkups()
    {
        return @$this->data['message']['reply_markup']['inline_keyboard'];
    }	
		

    /// Get the chat_id of the current message

    /**
     * \return the String users's chat_id.
     */
    public function ChatID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['chat']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['chat']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['chat']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }

        return $this->data['message']['chat']['id'];
    }

    /// Get the message_id of the current message

    /**
     * \return the String message_id.
     */
    public function MessageID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['message']['message_id'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['message_id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['message_id'];
        }

        return $this->data['message']['message_id'];
    }

    /// Get the reply_to_message message_id of the current message

    /**
     * \return the String reply_to_message message_id.
     */
    public function ReplyToMessageID()
    {
        return $this->data['message']['reply_to_message']['message_id'];
    }
	
    /// Get the reply_to_message text of the current message

    /**
     * \return the String reply_to_message text.
     */
    public function ReplyToMessageText()
    {
        return $this->data['message']['reply_to_message']['text'];
    }
	
    /// Get the reply_to_message from user username of the current message

    /**
     * \return the String reply_to_message from username.
     */
    public function ReplyToMessageFromUsername()
    {
        return $this->data['message']['reply_to_message']['from']['username'];
    }
	
    /// Get the reply_to_message from user first_name of the current message

    /**
     * \return the String reply_to_message from first_name.
     */
    public function ReplyToMessageFromUserFirstName()
    {
        return $this->data['message']['reply_to_message']['from']['first_name'];
    }
	
    /// Get the reply_to_message from user last_name of the current message

    /**
     * \return the String reply_to_message from last_name.
     */
    public function ReplyToMessageFromUserLastName()
    {
        return $this->data['message']['reply_to_message']['from']['last_name'];
    }	
	
    /// Get the reply_to_message from user id of the current message

    /**
     * \return the String reply_to_message from user id.
     */
    public function ReplyToMessageFromUserID()
    {
        return $this->data['message']['reply_to_message']['from']['id'];
    }	

    /// Get the reply_to_message forward_from user_id of the current message

    /**
     * \return the String reply_to_message forward_from user_id.
     */
    public function ReplyToMessageForwardFromUserID()
    {
        return $this->data['message']['reply_to_message']['forward_from']['id'];
    }

    /// Get the inline_query of the current update

    /**
     * \return the Array inline_query.
     */
    public function Inline_Query()
    {
        return $this->data['inline_query'];
    }
	
	public function Inline_Query_ID()
    {
        return $this->data['inline_query']['id'];
    }
	
	public function Inline_Query_Text()
    {
        return $this->data['inline_query']['query'];
    }	

    /// Get the callback_query of the current update

    /**
     * \return the String callback_query.
     */
    public function Callback_Query()
    {
        return $this->data['callback_query'];
    }

    /// Get the callback_query id of the current update

    /**
     * \return the String callback_query id.
     */
    public function Callback_ID()
    {
        return $this->data['callback_query']['id'];
    }

    /// Get the Get the data of the current callback

    /**
     * \deprecated Use Text() instead
     * \return the String callback_data.
     */
    public function Callback_Data()
    {
        return $this->data['callback_query']['data'];
    }

    /// Get the Get the message of the current callback

    /**
     * \return the Message.
     */
    public function Callback_Message()
    {
        return $this->data['callback_query']['message'];
    }

    /// Get the Get the chati_id of the current callback

    /**
     * \deprecated Use ChatId() instead
     * \return the String callback_query.
     */
    public function Callback_ChatID()
    {
        return $this->data['callback_query']['message']['chat']['id'];
    }

    /// Get the date of the current message

    /**
     * \return the String message's date.
     */
    public function Date()
    {
        return $this->data['message']['date'];
    }

    /// Get the first name of the user
    public function FirstName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['first_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['first_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['first_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['first_name'];
        }		

        return @$this->data['message']['from']['first_name'];
    }

    /// Get the last name of the user
    public function LastName()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['last_name'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['last_name'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['last_name'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['last_name'];
        }		
		

        return @$this->data['message']['from']['last_name'];
    }

    /// Get the username of the user
    public function Username()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['username'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['username'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['username'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['username'];
        }

        return @$this->data['message']['from']['username'];
    }
	
    /// Get the is_bot status of the user
    public function IsBot()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return @$this->data['callback_query']['from']['is_bot'];
        }
        if ($type == self::CHANNEL_POST) {
            return @$this->data['channel_post']['from']['is_bot'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['is_bot'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['is_bot'];
        }
        if ($type == self::FORWARDED) {
            return $this->data['message']['forward_from']['is_bot'];
        }
		
        return @$this->data['message']['from']['is_bot'];
    }	
	
    /// return the mew_chat_member Array of the current message
    public function NewChatMember()
    {
        return $this->data['message']['new_chat_members'];
    }	

    /// Get the location in the message
    public function Location()
    {
        return $this->data['message']['location'];
    }

    /// Get the update_id of the message
    public function UpdateID()
    {
        return $this->data['update_id'];
    }

    /// Get the number of updates
    public function UpdateCount()
    {
        return count($this->updates['result']);
    }

    /// Get user's id of current message
    public function UserID()
    {
        $type = $this->getUpdateType();
        if ($type == self::CALLBACK_QUERY) {
            return $this->data['callback_query']['from']['id'];
        }
        if ($type == self::CHANNEL_POST) {
            return $this->data['channel_post']['from']['id'];
        }
        if ($type == self::EDITED_MESSAGE) {
            return @$this->data['edited_message']['from']['id'];
        }
        if ($type == self::INLINE_QUERY) {
            return @$this->data['inline_query']['from']['id'];
        }			

        return $this->data['message']['from']['id'];
    }
	
	// MOHSEN ADDED FUNCTIONS //
	
	/// return contact phone Number
	public function getContactPhoneNumber()
	{

			return isset($this->data["message"]["contact"]["phone_number"]) ? $this->data["message"]["contact"]["phone_number"] : "";
	}
	
	/// return contact first_name
	public function getContactFirstName()
	{

			return isset($this->data["message"]["contact"]["first_name"]) ? $this->data["message"]["contact"]["first_name"] : "";
	}
    /// return contact first_name
    public function getContactUserId()
    {

            return isset($this->data["message"]["contact"]["user_id"]) ? $this->data["message"]["contact"]["user_id"] : "";
    }
    /// return latitude
	public function Latitude()
	{
		if ($this->getUpdateType() == 'location')
			return $this->data["message"]["location"]["latitude"];
	}
	
	/// return longitude
	public function Longitude()
	{
		if ($this->getUpdateType() == 'location')
			return $this->data["message"]["location"]["longitude"];
	}	
	
	
	/// Get photo file_id of current message
	public function photoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][0]["file_id"];
	}
	
	/// Get small size photo file_id of current message
	public function smallPhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][0]["file_id"];
	}
	
	/// Get middle size photo file_id of current message
	public function middlePhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][1]["file_id"];
	}
	
	/// Get big size photo file_id of current message
	public function bigPhotoFileID()
	{
		if ($this->getUpdateType() == 'photo')
			return $this->data["message"]["photo"][2]["file_id"];
	}
	
	/// Get voice file_id of current message
	public function voiceFileID()
	{
		if ($this->getUpdateType() == 'voice')
			return $this->data["message"]["voice"]["file_id"];
	}
	
	/// Get video file_id of current message
	public function videoFileID()
	{
		if ($this->getUpdateType() == 'video')
			return $this->data["message"]["video"]["file_id"];
	}
	
	/// Get audio file_id of current message
	public function audioFileID()
	{
		if ($this->getUpdateType() == 'audio')
			return $this->data["message"]["audio"]["file_id"];
	}
	
	/// Get document file_id of current message
	public function documentFileID()
	{
		if ($this->getUpdateType() == 'document')
			return $this->data["message"]["document"]["file_id"];
	}
	
	/// Get document mime_type of current message
	public function documentMimeType()
	{
		if ($this->getUpdateType() == 'document')
			return $this->data["message"]["document"]["mime_type"];
	}
	
	/// Get document mime_type extension of current message -- return 3 character's of file extenstion
	public function getDocumentMimeTypeExtension()
	{
		if ($this->getUpdateType() == 'document'){
			$mime_type = $this->data["message"]["document"]["mime_type"];
			if($mime_type == 'application/vnd.android.package-archive')
				return 'apk';
			elseif($mime_type == 'application/zip')
				return 'zip';
			elseif($mime_type == 'application/x-rar')
				return 'rar';
			elseif($mime_type == 'application/msword')
				return 'doc';
			elseif($mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
				return 'docx';
			elseif($mime_type == 'application/vnd.ms-excel')
				return 'xls';			
			elseif($mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
				return 'xlsx';			
			elseif($mime_type == 'application/javascript')
				return 'js';			
			elseif($mime_type == 'application/x-php')
				return 'php';
			elseif($mime_type == 'text/x-sql')
				return 'sql';			
		}
	}	
	
	// MOHSEN ADDED FUNCTIONS //

    /// Get user's id of current forwarded message
    public function FromID()
    {
        return $this->data['message']['forward_from']['id'];
    }

    /// Get chat's id where current message forwarded from
    public function FromChatID()
    {
        return $this->data['message']['forward_from_chat']['id'];
    }

    /// Tell if a message is from a group or user chat

    /**
     *  \return BOOLEAN true if the message is from a Group chat, false otherwise.
     */
    public function messageFromGroup()
    {
        if ($this->data['message']['chat']['type'] == 'private') {
            return false;
        }

        return true;
    }
    
    public function getChatType()
    {
        return $this->data['message']['chat']['type'];
    }

    /// Get the title of the group chat

    /**
     *  \return a String of the title chat.
     */
    public function messageFromGroupTitle()
    {
        if ($this->data['message']['chat']['type'] != 'private') {
            return $this->data['message']['chat']['title'];
        }
    }

    /// Set a custom keyboard

    /** This object represents a custom keyboard with reply options
     * \param $options Array of Array of String; Array of button rows, each represented by an Array of Strings
     * \param $onetime Boolean Requests clients to hide the keyboard as soon as it's been used. Defaults to false.
     * \param $resize Boolean Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested keyboard as Json.
     */
    public function buildKeyBoard(array $options, $onetime = false, $resize = false, $selective = true)
    {
        $replyMarkup = [
            'keyboard'          => $options,
            'one_time_keyboard' => $onetime,
            'resize_keyboard'   => $resize,
            'selective'         => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup);

        return $encodedMarkup;
    }

    /// Set an InlineKeyBoard

    /** This object represents an inline keyboard that appears right next to the message it belongs to.
     * \param $options Array of Array of InlineKeyboardButton; Array of button rows, each represented by an Array of InlineKeyboardButton
     * \return the requested keyboard as Json.
     */
    public function buildInlineKeyBoard(array $options)
    {
        $replyMarkup = [
            'inline_keyboard' => $options,
        ];
        $encodedMarkup = json_encode($replyMarkup);

        return $encodedMarkup;
    }

    /// Create an InlineKeyboardButton

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * \param $text String; Array of button rows, each represented by an Array of Strings
     * \param $url String Optional. HTTP url to be opened when button is pressed
     * \param $callback_data String Optional. Data to be sent in a callback query to the bot when button is pressed
     * \param $switch_inline_query String Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot‘s username and the specified inline query in the input field. Can be empty, in which case just the bot’s username will be inserted.
     * \param $switch_inline_query_current_chat String Optional. Optional. If set, pressing the button will insert the bot‘s username and the specified inline query in the current chat's input field. Can be empty, in which case only the bot’s username will be inserted.
     * \param $callback_game  String Optional. Description of the game that will be launched when the user presses the button.
     * \param $pay  Boolean Optional. Specify True, to send a <a href="https://core.telegram.org/bots/api#payments">Pay button</a>.
     * \return the requested button as Array.
     */
    public function buildInlineKeyboardButton($text, $url = '', $callback_data = '', $switch_inline_query = null, $switch_inline_query_current_chat = null, $callback_game = '', $pay = '')
    {
        $replyMarkup = [
            'text' => $text,
        ];
        if ($url != '') {
            $replyMarkup['url'] = $url;
        } elseif ($callback_data != '') {
            $replyMarkup['callback_data'] = $callback_data;
        } elseif (!is_null($switch_inline_query)) {
            $replyMarkup['switch_inline_query'] = $switch_inline_query;
        } elseif (!is_null($switch_inline_query_current_chat)) {
            $replyMarkup['switch_inline_query_current_chat'] = $switch_inline_query_current_chat;
        } elseif ($callback_game != '') {
            $replyMarkup['callback_game'] = $callback_game;
        } elseif ($pay != '') {
            $replyMarkup['pay'] = $pay;
        }

        return $replyMarkup;
    }

    /// Create a KeyboardButton

    /** This object represents one button of an inline keyboard. You must use exactly one of the optional fields.
     * \param $text String; Array of button rows, each represented by an Array of Strings
     * \param $request_contact Boolean Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only
     * \param $request_location Boolean Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only
     * \return the requested button as Array.
     */
    public function buildKeyboardButton($text, $request_contact = false, $request_location = false)
    {
        $replyMarkup = [
            'text'             => $text,
            'request_contact'  => $request_contact,
            'request_location' => $request_location,
        ];

        return $replyMarkup;
    }

    /// Hide a custom keyboard

    /** Upon receiving a message with this object, Telegram clients will hide the current custom keyboard and display the default letter-keyboard. By default, custom keyboards are displayed until a new keyboard is sent by a bot. An exception is made for one-time keyboards that are hidden immediately after the user presses a button.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested keyboard hide as Array.
     */
    public function buildKeyBoardHide($selective = true)
    {
        $replyMarkup = [
            'remove_keyboard' => true,
            'selective'       => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup);

        return $encodedMarkup;
    }

    /// Display a reply interface to the user
    /* Upon receiving a message with this object, Telegram clients will display a reply interface to the user (act as if the user has selected the bot‘s message and tapped ’Reply'). This can be extremely useful if you want to create user-friendly step-by-step interfaces without having to sacrifice privacy mode.
     * \param $selective Boolean Use this parameter if you want to show the keyboard to specific users only. Targets: 1) users that are @mentioned in the text of the Message object; 2) if the bot's message is a reply (has reply_to_message_id), sender of the original message.
     * \return the requested force reply as Array
     */
    public function buildForceReply($selective = true)
    {
        $replyMarkup = [
            'force_reply' => true,
            'selective'   => $selective,
        ];
        $encodedMarkup = json_encode($replyMarkup);

        return $encodedMarkup;
    }

    // Payments
    /// Send an invoice

    public function sendInvoice(array $content)
    {
        return $this->endpoint('sendInvoice', $content);
    }

    /// Answer a shipping query

    public function answerShippingQuery(array $content)
    {
        return $this->endpoint('answerShippingQuery', $content);
    }

    /// Answer a PreCheckout query

    public function answerPreCheckoutQuery(array $content)
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }

    /// Send a video note

    public function sendVideoNote(array $content)
    {
        return $this->endpoint('sendVideoNote', $content);
    }

    /// Restrict Chat Member

    public function restrictChatMember(array $content)
    {
        return $this->endpoint('restrictChatMember', $content);
    }

    /// Promote Chat Member

    public function promoteChatMember(array $content)
    {
        return $this->endpoint('promoteChatMember', $content);
    }

    //// Export Chat Invite Link

    public function exportChatInviteLink(array $content)
    {
        return $this->endpoint('exportChatInviteLink', $content);
    }

    /// Set Chat Photo

    public function setChatPhoto(array $content)
    {
        return $this->endpoint('setChatPhoto', $content);
    }

    /// Delete Chat Photo

    public function deleteChatPhoto(array $content)
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }

    /// Set Chat Title

    public function setChatTitle(array $content)
    {
        return $this->endpoint('setChatTitle', $content);
    }

    /// Set Chat Description

    public function setChatDescription(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }
	
	///  Set Admin Custome Title
	
    public function setChatAdministratorCustomTitle(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }	
	

    /// Pin Chat Message

    public function pinChatMessage(array $content)
    {
        return $this->endpoint('pinChatMessage', $content);
    }

    /// Unpin Chat Message

    public function unpinChatMessage(array $content)
    {
        return $this->endpoint('unpinChatMessage', $content);
    }

    /// Get Sticker Set

    public function getStickerSet(array $content)
    {
        return $this->endpoint('getStickerSet', $content);
    }

    /// Upload Sticker File

    public function uploadStickerFile(array $content)
    {
        return $this->endpoint('uploadStickerFile', $content);
    }

    /// Create New Sticker Set

    public function createNewStickerSet(array $content)
    {
        return $this->endpoint('createNewStickerSet', $content);
    }

    /// Add Sticker To Set

    public function addStickerToSet(array $content)
    {
        return $this->endpoint('addStickerToSet', $content);
    }

    /// Set Sticker Position In Set

    public function setStickerPositionInSet(array $content)
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }

    /// Delete Sticker From Set

    public function deleteStickerFromSet(array $content)
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }

    /// Delete a message

    public function deleteMessage(array $content)
    {
        return $this->endpoint('deleteMessage', $content);
    }

    /// Receive incoming messages using polling

    /** Use this method to receive incoming updates using long polling.
     * \param $offset Integer Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id.
     * \param $limit Integer Limits the number of updates to be retrieved. Values between 1—100 are accepted. Defaults to 100
     * \param $timeout Integer Timeout in seconds for long polling. Defaults to 0, i.e. usual short polling
     * \param $update Boolean If true updates the pending message list to the last update received. Default to true.
     * \return the updates as Array.
     */
    public function getUpdates($offset = 0, $limit = 100, $timeout = 0, $update = true)
    {
        $content = ['offset' => $offset, 'limit' => $limit, 'timeout' => $timeout];
        $this->updates = $this->endpoint('getUpdates', $content);
        if ($update) {
            if (count($this->updates['result']) >= 1) { //for CLI working.
                $last_element_id = $this->updates['result'][count($this->updates['result']) - 1]['update_id'] + 1;
                $content = ['offset' => $last_element_id, 'limit' => '1', 'timeout' => $timeout];
                $this->endpoint('getUpdates', $content);
            }
        }

        return $this->updates;
    }

    /// Serve an update

    /** Use this method to use the bultin function like Text() or Username() on a specific update.
     * \param $update Integer The index of the update in the updates array.
     */
    public function serveUpdate($update)
    {
        $this->data = $this->updates['result'][$update];
    }
    
    // return Dice Value
    function getDiceValue(){
        return $this->data['message']['dice']['value'];
    }

    /// Return current update type

    /**
     * Return current update type `False` on failure.
     *
     * @return bool|string
     */
    public function getUpdateType()
    {
        $update = $this->data;
        if (isset($update['inline_query'])) {
            return self::INLINE_QUERY;
        }
        if (isset($update['callback_query'])) {
            return self::CALLBACK_QUERY;
        }
        if (isset($update['message']['reply_to_message'])) {
			if(isset($update['message']['animation']))
				return self::ANIMATION;
			elseif(isset($update['message']['document']))
				return self::DOCUMENT;
			elseif(isset($update['message']['sticker']))
				return self::STICKER;
			elseif(isset($update['message']['photo']))
				return self::PHOTO;
			elseif(isset($update['message']['video']))
				return self::VIDEO;
			elseif(isset($update['message']['audio']))
				return self::AUDIO;
			elseif(isset($update['message']['voice']))
				return self::VOICE;
            else
				return self::REPLY;
        }		
        if (isset($update['edited_channel_post'])) {
            return self::EDITED_CHANNEL_POST;
        }
		if (isset($update['edited_message'])) {
            return self::EDITED_MESSAGE;
        }
        if (isset($update['message']['forward_from']) || isset($update['message']['forward_from_chat'])) {
            return self::FORWARDED;
        }
        if (isset($update['message']['new_chat_members'])) {
            return self::NEW_CHAT_MEMBER;
        }
        if (isset($update['message']['left_chat_member'])) {
            return self::LEFT_CHAT_MEMBER;
        }        
        if (isset($update['message']['text'])) {
            return self::MESSAGE;
        }		
        if (isset($update['message']['photo'])) {
            return self::PHOTO;
        }
        if (isset($update['message']['video'])) {
            return self::VIDEO;
        }
        if (isset($update['message']['audio'])) {
            return self::AUDIO;
        }
        if (isset($update['message']['voice'])) {
            return self::VOICE;
        }
        if (isset($update['message']['contact'])) {
            return self::CONTACT;
        }
        if (isset($update['message']['location'])) {
            return self::LOCATION;
        }
        if (isset($update['message']['document'])) {
            return self::DOCUMENT;
		}
        if (isset($update['message']['animation'])) {
            return self::ANIMATION;
        }
		if (isset($update['message']['sticker'])) {
            return self::STICKER;
        }
        if (isset($update['channel_post'])) {
            return self::CHANNEL_POST;
        }
        if (isset($update['message']['dice'])) {
            return self::DICE;
        }

        return false;
    }

    private function sendAPIRequest($url, array $content, $post = true)
    {
        if (isset($content['chat_id'])) {
            $url = $url.'?chat_id='.$content['chat_id'];
            unset($content['chat_id']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        }

		if (!empty($this->proxy)) {

			if (array_key_exists("type", $this->proxy)) {
				curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy["type"]);
			}
			
			if (array_key_exists("auth", $this->proxy)) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy["auth"]);
			}
			
			if (array_key_exists("url", $this->proxy)) {

				curl_setopt($ch, CURLOPT_PROXY, $this->proxy["url"]);
			}
			
			if (array_key_exists("port", $this->proxy)) {

				curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy["port"]);
			}
			
		}
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }

        curl_close($ch);
        if ($this->log_errors) {
            if (class_exists('TelegramErrorLogger')) {
                $loggerArray = ($this->getData() == null) ? [$content] : [$this->getData(), $content];
                TelegramErrorLogger::log(json_decode($result, true), $loggerArray);
            }
        }


        return $result;
    }
}

// Helper for Uploading file using CURL
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '')
    {
        return "@$filename;filename="
        .($postname ?: basename($filename))
        .($mimetype ? ";type=$mimetype" : '');
    }
}