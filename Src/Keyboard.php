<?php

namespace Makhnanov\TelegramSeaBattle;

use JetBrains\PhpStorm\ArrayShape;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Makhnanov\Telegram81\Emoji\Constant\EmojiSymbol;
use Makhnanov\Telegram81\Snippet\LanguageSnippetEnum as Language;
use UnitEnum;

use function Makhnanov\Telegram81\callbackButton;

class Keyboard
{
    #[ArrayShape(['inline_keyboard' => "array[]"])]
    public static function choseSkin(Language $language, ?UnitEnum $current = null): array
    {
        return InlineKeyboardMarkup::new([
            callbackButton(self::mark(Skin::emoji, $current, $language), Skin::emoji->name),
            callbackButton(self::mark(Skin::water, $current, $language), Skin::water->name),
            callbackButton(self::mark(Skin::acid, $current, $language), Skin::acid->name),
        ]);
    }

    private static function mark(Skin $compare, $current, Language $language): string
    {
        if ($current && $compare === $current) {
            $addCheck = ' ' . EmojiSymbol::GREEN_CHECK;
        }
        return $compare->translate($language) . ($addCheck ?? '');
    }
}
