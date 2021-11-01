<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;
use Makhnanov\TelegramSeaBattle\Keyboard;
use Makhnanov\TelegramSeaBattle\PhotoId;
use Makhnanov\TelegramSeaBattle\Skin;

/**
 * @property Skin $enum
 */
class SkinHandler extends CallbackHandler
{
    public function handle(): void
    {
        $language = $this->user->getLanguage();
        $this->game->getBot()->editMessageMedia(
            [
                'type' => 'photo',
                'media' => PhotoId::ALL_SKINS
            ],
            $this->chat_id,
            $this->message_id,
            reply_markup: Keyboard::choseSkin($language, $this->enum)
        );
    }
}
