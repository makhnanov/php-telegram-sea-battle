<?php

namespace Makhnanov\TelegramSeaBattle\Callback;

use UnitEnum;
use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\TelegramSeaBattle\SeaBattleGame;

abstract class CallbackHandler
{
    protected string $callback_data;

    protected int $chat_id;

    protected int $message_id;

    public function __construct(
        protected SeaBattleGame $game,
        protected Update $update,
        protected UnitEnum $enum,
    ) {
        $this->callback_data = $update->callback_query->data;
        $this->chat_id = $update->callback_query->from->id;
        $this->message_id = $update->callback_query->message->message_id;
    }

    abstract public function handle(): void;
}
