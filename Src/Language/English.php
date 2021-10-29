<?php

namespace Makhnanov\TelegramSeaBattle\Language;

use Makhnanov\Telegram81\Helper\Smile\SmileFlag;
use Makhnanov\Telegram81\Helper\Smile\SmileObject;

class English implements Translator
{
    public static function yourLanguage(): string
    {
        return 'Your language: english. ' . SmileFlag::USA;
    }

    public static function change(): string
    {
        return 'Change ' . SmileObject::PEN;
    }
}