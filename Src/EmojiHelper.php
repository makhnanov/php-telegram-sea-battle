<?php

namespace Makhnanov\TelegramSeaBattle;

class EmojiHelper
{
    private const EMOJI = [
        '🕸',
        '🎯',
        '☠️',
        '👻',
        '👆',
        '0️⃣',
        '1️⃣',
        '2️⃣',
        '3️⃣',
        '4️⃣',
        '5️⃣',
        '6️⃣',
        '7️⃣',
        '8️⃣',
        '9️⃣',
        '🔟',
        '#️⃣',
        //        '',
    ];

    public static function splitByEmoji(string $emojiString): array
    {
        $matches = [];
        preg_match_all('/' . implode('|', self::EMOJI) . '/', $emojiString, $matches);
        return $matches[0] ?? [];
    }
}
