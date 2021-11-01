<?php

namespace Makhnanov\TelegramSeaBattle;

class Logger
{
    public static function log(mixed $mixed)
    {
        if (is_string($mixed)) {
            dump(date('[Y-m-d H:i:s] ') . $mixed);
        } else {
            dump(date('[Y-m-d H:i:s] '));
            dump($mixed);
        }
    }
}
