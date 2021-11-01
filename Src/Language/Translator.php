<?php

namespace Makhnanov\TelegramSeaBattle\Language;

use Makhnanov\TelegramSeaBattle\Skin;

interface Translator
{
    public static function yourLanguage(): string;

    public static function change(): string;

    public static function chooseSkin(): string;

    public static function skin(Skin $enum): string;
}
