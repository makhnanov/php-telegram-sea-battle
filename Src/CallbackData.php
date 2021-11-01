<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\UpgradeEnum;

enum CallbackData
{
    use UpgradeEnum;

    case CHANGE_LANGUAGE;
}
