<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\UpgradeEnum;

enum Lang: string
{
    use UpgradeEnum;

    case RUSSIAN = 'Русский 🇷🇺';
    case ENGLISH = 'English 🇺🇸';
}
