<?php

namespace Makhnanov\TelegramSeaBattle;

use UnitEnum;

use function Makhnanov\PhpEnum81\name;

function getenv(UnitEnum $enum): array|false|string
{
    return \getenv(name($enum));
}
