<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\Keyboard;
use Makhnanov\TelegramSeaBattle\Message;

class Other extends CallbackHandler
{
    public function handle(): void
    {
        match ($this->enum) {
            CallbackData::CHANGE_LANGUAGE => $this->game->getBot()->editMessageText(
                Message::$changeLanguage,
                $this->chat_id,
                $this->message_id,
                reply_markup: InlineKeyboardMarkup::new(Keyboard::languages())
            ),
        };
    }
}
