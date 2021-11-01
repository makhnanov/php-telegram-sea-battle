<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use Makhnanov\Telegram81\Snippet\KeyboardSnippet;
use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\Message;

class OtherCallbackHandler extends CallbackHandler
{
    public function handle(): void
    {
        match ($this->enum) {
            CallbackData::CHANGE_LANGUAGE => $this->game->getBot()->editMessageText(
                Message::$changeLanguage,
                $this->chat_id,
                $this->message_id,
                reply_markup: KeyboardSnippet::russianEnglish()
            ),
        };
    }
}
