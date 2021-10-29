<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\EnumUpgrade;

enum CallbackData
{
    use EnumUpgrade;

    case CHANGE_LANGUAGE;
}
