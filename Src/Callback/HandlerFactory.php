<?php

namespace Makhnanov\TelegramSeaBattle\Callback;

use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Emoji\Enumeration\JoystickEnum;
use Makhnanov\Telegram81\Snippet\LanguageSnippetEnum;
use Makhnanov\TelegramSeaBattle\Callback\Handler\JoystickHandler;
use Makhnanov\TelegramSeaBattle\Callback\Handler\LanguageHandler;
use Makhnanov\TelegramSeaBattle\Callback\Handler\OtherCallbackHandler;
use Makhnanov\TelegramSeaBattle\Callback\Handler\SkinHandler;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\SeaBattleGame;
use Makhnanov\TelegramSeaBattle\Skin;

use function Makhnanov\PhpEnum81\get_enum;

class HandlerFactory
{
    public function __construct(
        protected SeaBattleGame $game,
        protected Update        $update,
    ) {
    }

    public function handleEnum(string $enumClass): void
    {
        $callbackData = $this->update->callback_query->data;
        $handlerClass = match ($enumClass) {
            LanguageSnippetEnum::class => LanguageHandler::class,
            JoystickEnum::class => JoystickHandler::class,
            CallbackData::class => OtherCallbackHandler::class,
            Skin::class => SkinHandler::class,
        };
        /** @var CallbackHandler $handler */
        $handler = new $handlerClass(
            $this->game,
            $this->update,
            get_enum($enumClass, $callbackData),
        );
        $handler->handle();
    }

    public function handleDefault()
    {
        echo 'Unhandled callback ' . $this->update->update_id;
    }
}
