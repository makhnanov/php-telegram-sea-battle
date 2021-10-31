<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Makhnanov\TelegramSeaBattle;

use Stringable;
use UnitEnum;

use function Makhnanov\PhpEnum81\name;

function getenv(UnitEnum $enum): array|false|string
{
    return \getenv(name($enum));
}

function log(mixed $var)
{
    if (is_string($var) || $var instanceof Stringable) {
        dump( ' ' . $var);
    }
}