<?php

namespace Makhnanov\TelegramSeaBattle\Language;

interface Translator
{
    public static function yourLanguage(): string;

    public static function change(): string;
}
