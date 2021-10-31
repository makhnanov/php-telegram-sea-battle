<?php

namespace Makhnanov\TelegramSeaBattle\Callback;

use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Helper\Smile\SmileJoystick;
use Makhnanov\TelegramSeaBattle\Callback\Handler\Joystick;
use Makhnanov\TelegramSeaBattle\Callback\Handler\Language;
use Makhnanov\TelegramSeaBattle\Callback\Handler\Other;
use Makhnanov\TelegramSeaBattle\CallbackData;
use Makhnanov\TelegramSeaBattle\LangEnum;
use Makhnanov\TelegramSeaBattle\SeaBattleGame;

use function Makhnanov\PhpEnum81\get_enum;

class HandlerFactory
{
    public function __construct(
        protected SeaBattleGame $game,
        protected Update $update,
    ) {
    }

    public function handleEnum(string $enumClass): void
    {
        $callbackData = $this->update->callback_query->data;
        $handlerClass = match ($enumClass) {
            LangEnum::class => Language::class,
            SmileJoystick::class => Joystick::class,
            CallbackData::class => Other::class,
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
