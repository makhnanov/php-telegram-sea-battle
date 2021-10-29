<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\EnumUpgrade;

enum LangEnum: string
{
    use EnumUpgrade;

    case RUSSIAN = 'Русский 🇷🇺';
    case ENGLISH = 'English 🇺🇸';
}
