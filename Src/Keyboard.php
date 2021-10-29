<?php

namespace Makhnanov\TelegramSeaBattle;

use function Makhnanov\Telegram81\callbackButton;

class Keyboard
{
    public static function languages(): array
    {
        return [callbackButton(LangEnum::RUSSIAN), callbackButton(LangEnum::ENGLISH)];
    }
}
