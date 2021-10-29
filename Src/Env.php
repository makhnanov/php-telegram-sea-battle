<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\EnumUpgrade;

enum Env
{
    use EnumUpgrade;

    case TOKEN;
    case REDIS_HOST;
}
