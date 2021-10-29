<?php

namespace Makhnanov\TelegramSeaBattle\Language;

use Makhnanov\Telegram81\Helper\Smile\SmileFlag;
use Makhnanov\Telegram81\Helper\Smile\SmileObject;

class Russian implements Translator
{
    public static function yourLanguage(): string
    {
        return 'Ваш язык: русский. ' . SmileFlag::RUSSIA;
    }

    public static function change(): string
    {
        return 'Изменить ' . SmileObject::PEN;
    }
}
