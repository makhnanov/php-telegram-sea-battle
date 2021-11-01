<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\UpgradeEnum;

enum Env
{
    use UpgradeEnum;

    case TOKEN;
    case REDIS_HOST;
}
