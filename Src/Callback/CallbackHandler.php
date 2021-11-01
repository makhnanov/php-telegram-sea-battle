<?php

namespace Makhnanov\TelegramSeaBattle\Callback;

use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Helper\ResultativeInterface;
use Makhnanov\TelegramSeaBattle\SeaBattleGame;
use Makhnanov\TelegramSeaBattle\User;
use UnitEnum;

abstract class CallbackHandler
{
    protected string $callback_data;

    protected int $chat_id;

    protected int $message_id;

    protected User $user;

    public function __construct(
        protected SeaBattleGame                 $game,
        protected Update & ResultativeInterface $update,
        protected UnitEnum                      $enum,
    ) {
        $this->callback_data = $update->callback_query->data;
        $this->chat_id = $update->callback_query->from->id;
        $this->message_id = $update->callback_query->message->message_id;
        $this->user = new User($game->getRedis(), $this->chat_id);
    }

    abstract public function handle(): void;
}
