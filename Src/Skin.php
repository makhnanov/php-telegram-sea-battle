<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\PhpEnum81\UpgradedEnumInterface;
use Makhnanov\PhpEnum81\UpgradeEnum;
use Makhnanov\Telegram81\Snippet\LanguageSnippetEnum;

enum Skin implements UpgradedEnumInterface
{
    use UpgradeEnum;

    case emoji;
    case water;
    case acid;

    public function translate(LanguageSnippetEnum $language): string
    {
        if ($language === LanguageSnippetEnum::ENGLISH) {
            return ucfirst($this->name);
        }
        return match ($this) {
            self::emoji => 'Смайл',
            self::water => 'Вода',
            self::acid => 'Кислота'
        };
    }
}
