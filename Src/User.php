<?php

namespace Makhnanov\TelegramSeaBattle;

use Makhnanov\Telegram81\Snippet\LanguageSnippetEnum;
use Makhnanov\TelegramSeaBattle\Exception\UndefinedLanguage;
use Makhnanov\TelegramSeaBattle\Language\English;
use Makhnanov\TelegramSeaBattle\Language\Russian;
use Makhnanov\TelegramSeaBattle\Language\Translator;
use Predis\Client;

class User
{
    public function __construct(
        protected Client     $redis,
        protected int|string $chat_id,
    ) {
    }

    public function setLanguage(LanguageSnippetEnum $enum): Translator
    {
        $this->redis->hset($this->key(), UserEnum::language->name, $enum->name);
        return $enum === LanguageSnippetEnum::RUSSIAN ? new Russian() : new English();
    }

    /**
     * @throws UndefinedLanguage
     */
    public function getTranslator(): Translator
    {
        $language = $this->redis->hget($this->key(), UserEnum::language->name);
        $enum = LanguageSnippetEnum::byName($language) ?? throw new UndefinedLanguage();
        return $enum === LanguageSnippetEnum::RUSSIAN ? new Russian() : new English();
    }

    /**
     * @throws UndefinedLanguage
     */
    public function getLanguage(): LanguageSnippetEnum
    {
        $language = $this->redis->hget($this->key(), UserEnum::language->name);
        return LanguageSnippetEnum::byName($language) ?? throw new UndefinedLanguage();
    }

    public function setSkin()
    {
        return;
    }


    public function key(): string
    {
        return 'user' . $this->chat_id;
    }
}
