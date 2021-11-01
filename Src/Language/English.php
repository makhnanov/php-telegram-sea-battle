<?php

namespace Makhnanov\TelegramSeaBattle\Language;

use Makhnanov\Telegram81\Emoji\Constant\EmojiFlag;
use Makhnanov\Telegram81\Emoji\Constant\EmojiObject;
use Makhnanov\TelegramSeaBattle\Skin;

class English implements Translator
{
    public static function yourLanguage(): string
    {
        return 'Your language: english. ' . EmojiFlag::USA;
    }

    public static function change(): string
    {
        return 'Change ' . EmojiObject::PEN;
    }

    public static function chooseSkin(): string
    {
        return 'Please, choose skin:';
    }

    public static function skin(Skin $enum): string
    {
        return "Skin: {$enum->name}.";
    }
}
