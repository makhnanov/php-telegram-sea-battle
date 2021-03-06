<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\Keyboard;
use Makhnanov\TelegramSeaBattle\Language\Translator;
use Makhnanov\TelegramSeaBattle\PhotoId;

use function Makhnanov\Telegram81\callbackButton;

class LanguageHandler extends CallbackHandler
{
    public function handle(): void
    {
        /** @noinspection PhpParamsInspection */
        $translator = $this->user->setLanguage($this->enum);
        $this->game->getBot()->editMessageText(
            $translator->yourLanguage(),
            $this->chat_id,
            $this->message_id,
            reply_markup: InlineKeyboardMarkup::new([
                callbackButton($translator->change(), CallbackData::CHANGE_LANGUAGE->name)
            ])
        );
        $this->chooseFieldType($translator);
    }

    //    public function sendField(Update $update): void
    //    {
    //        $this->game->getBot()->sendMessage(
    //            $this->chat_id,
    //            'π«π«π«π«π«π«π«π«π«π«π«π«
    //π«πΈπ―πΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«πΈπ»πΈπ―π―π―πΈπΈπ―πΈπ«
    //π«πΈπ»πΈπ―β οΈπ―πΈπΈπΈπΈπ«
    //π«πΈππΈπ―β οΈπ―πΈπΈπΈπΈπ«
    //π«πΈπ»π―π―β οΈπ―πΈπΈπΈπΈπ«
    //π«πΈπ―πΈπ―π―π―πΈπΈπΈπΈπ«
    //π«πΈπΈπΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«πΈπΈπΈπΈπΈπΈπΈπ―πΈπΈπ«
    //π«πΈπΈπ―πΈπΈπΈπΈπΈπΈπΈπ«
    //π«πΈπΈπΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«0οΈβ£1οΈβ£2οΈβ£3οΈβ£4οΈβ£5οΈβ£6οΈβ£7οΈβ£8οΈβ£9οΈβ£π«
    //π«πΈπΈπΈπΈπΈπ’πΈπΈπΈπΈπ«
    //π«π’πΈπΈπΈπΈπΈπΈπΈπ’πΈπ«
    //π«π’πΈπΈπ’πΈπΈπΈπΈπ’πΈπ«
    //π«π’πΈπΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«π’πΈπΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«πΈπΈπΈπ’πΈπΈπ’πΈπΈπΈπ«
    //π«πΈπΈπΈπΈπΈπΈπ’πΈπΈπΈπ«
    //π«πΈπΈπΈπΈπΈπΈπΈπΈπΈπΈπ«
    //π«π’π’π’πΈπΈπ’πΈπΈπ’πΈπ«
    //π«πΈπΈπΈπΈπΈπΈπΈπΈπ’πΈπ«
    //π«π«π«π«π«π«π«π«π«π«',
    //            reply_markup: Snippet::inlneJoystick()
    //        );
    //    }

    private function chooseFieldType(Translator $translator)
    {
        $this->game->getBot()->sendPhoto(
            $this->chat_id,
            PhotoId::ALL_SKINS,
            $translator->chooseSkin(),
            reply_markup: Keyboard::choseSkin($this->user->getLanguage())
        );
    }
}
