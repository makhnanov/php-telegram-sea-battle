<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Helper\ResultativeInterface;
use Makhnanov\Telegram81\Helper\Snippet;
use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\LangEnum;
use Makhnanov\TelegramSeaBattle\Language\English;
use Makhnanov\TelegramSeaBattle\Language\Russian;
use Makhnanov\TelegramSeaBattle\Language\Translator;

use function Makhnanov\Telegram81\callbackButton;

class Language extends CallbackHandler
{
    public function handle(): void
    {
        $this->game->getRedis()->hset('user' . $this->chat_id, 'language', $this->callback_data);
        /** @noinspection PhpParamsInspection */
        $translator = $this->getTranslator($this->enum);
        $this->game->getBot()->editMessageText(
            $translator->yourLanguage(),
            $this->chat_id,
            $this->message_id,
            reply_markup: InlineKeyboardMarkup::new([
                callbackButton($translator->change(), CallbackData::CHANGE_LANGUAGE->name)
            ])
        );
        $this->sendField($this->update);
    }

    #[Pure]
    private function getTranslator(LangEnum $enum = null): Translator
    {
        return $enum === LangEnum::ENGLISH ? new English() : new Russian();
    }

    public function sendField(Update & ResultativeInterface $update): void
    {
        $chat = $update->callback_query?->message->chat;
        $this->game->getBot()->sendMessage(
            $chat->id,
            '🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫
🌫🕸🎯🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸👻🕸🎯🎯🎯🕸🕸🎯🕸🌫
🌫🕸👻🕸🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸👆🕸🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸👻🎯🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸🎯🕸🎯🎯🎯🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🎯🕸🕸🌫
🌫🕸🕸🎯🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫0️⃣1️⃣2️⃣3️⃣4️⃣5️⃣6️⃣7️⃣8️⃣9️⃣🌫
🌫🕸🕸🕸🕸🕸🚢🕸🕸🕸🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🚢🕸🌫
🌫🚢🕸🕸🚢🕸🕸🕸🕸🚢🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🚢🕸🕸🚢🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🚢🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🚢🚢🚢🕸🕸🚢🕸🕸🚢🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🚢🕸🌫
🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫',
            reply_markup: Snippet::inlneJoystick()
        );
    }
}
