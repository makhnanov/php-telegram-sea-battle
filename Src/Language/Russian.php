<?php

namespace Makhnanov\TelegramSeaBattle\Language;

use Makhnanov\Telegram81\Emoji\Constant\EmojiFlag;
use Makhnanov\TelegramSeaBattle\Skin;

class Russian implements Translator
{
    public static function yourLanguage(): string
    {
        return 'Ваш язык: русский. ' . EmojiFlag::RUSSIA;
    }

    public static function change(): string
    {
        return 'Изменить ' . EmojiFlag::USA;
    }

    public static function chooseSkin(): string
    {
        return 'Пожалуйста, выберите внешний вид:';
    }

    public static function skin(Skin $enum): string
    {
        $skin = match (true) {
            $enum === Skin::emoji => 'смайлики',
            $enum === Skin::water => 'вода',
            $enum === Skin::acid => 'кислота',
        };
        return "Внешний вид: $skin.";
    }
}
