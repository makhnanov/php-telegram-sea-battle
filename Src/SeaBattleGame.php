<?php

namespace Makhnanov\TelegramSeaBattle;

use InvalidArgumentException;
use Makhnanov\Telegram81\Api\B;
use Makhnanov\Telegram81\Api\Bot;
use Makhnanov\Telegram81\Api\Enumeration\AllowedUpdates;
use Makhnanov\Telegram81\Api\Exception\UnchangedMessageException;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Helper\ResultativeInterface;
use Makhnanov\Telegram81\Helper\Smile\SmileJoystick;
use Makhnanov\TelegramSeaBattle\Callback\HandlerFactory;
use Makhnanov\TelegramSeaBattle\Language\UnknownLanguage;
use Predis\Client;
use Yiisoft\Strings\StringHelper;

use function Makhnanov\Telegram81\isPrivate;

class SeaBattleGame
{
    private Bot $bot;

    private Client $redis;

    public function __construct()
    {
        $this->bot = new B(
            getenv(Env::TOKEN) ?: throw new InvalidArgumentException('Bot token must not be empty.')
        );
        $this->redis = new Client([
            'host' => getenv(
                Env::REDIS_HOST ?: throw new InvalidArgumentException('Redis host must not be empty.')
            )
        ]);
        echo 'Started at ' . date('Y-m-d H:i:s') . PHP_EOL;
    }

    public function getBot(): Bot
    {
        return $this->bot;
    }

    public function getRedis(): Client
    {
        return $this->redis;
    }

    public function loop(): void
    {
        do {
            $this->getUpdates();
        } while (true);
    }

    public function getUpdates()
    {
        $updates = $this->bot->getUpdates(allowed_updates: AllowedUpdates::names());
        $this->bot->handleStopSignal();
        foreach ($updates as $update) {
            $this->processUpdate($update);
        }
    }

    private function processUpdate(Update & ResultativeInterface $update): void
    {
        $text = $update?->message?->text;
        $callbackQueryData = $update?->callback_query?->data;
        try {
            match (true) {
                $this->isStart($text)    => $this->start($update),
                (bool)$callbackQueryData => $this->handleCallbackDataQuery($update),
                default                  => $this->unhandledType($update),
            };
        } catch (UnchangedMessageException $e) {
            dump('You send unchanged message.');
        }
    }

    private function isStart(?string $text): bool
    {
        return $text === '/start' || StringHelper::startsWith($text ?? '', '/start');
    }

    public function start(Update $update)
    {
        if (isPrivate($update?->message->chat)) {
            $chat_id = $update->message->chat->id;
            $this->bot->sendMessage(
                $chat_id,
                Message::$start,
                disable_notification: true,
            );
            $this->bot->sendMessage(
                $chat_id,
                Message::$changeLanguage,
                disable_notification: true,
                reply_markup: InlineKeyboardMarkup::new(Keyboard::languages())
            );
        }
    }

    /**
     * @throws UnknownLanguage|UnchangedMessageException
     */
    public function handleCallbackDataQuery(Update & ResultativeInterface $update): void
    {
        $chat = $update->callback_query?->message->chat;
        $callbackQueryData = $update->callback_query->data;
        if (isPrivate($chat)) {
            dump(date('[Y-m-d H:i:s] ') . 'Private callback.');
            $factory = new HandlerFactory($this, $update);
            match (true) {
                LangEnum::exist($callbackQueryData)      => $factory->handleEnum(LangEnum::class),
                CallbackData::exist($callbackQueryData)  => $factory->handleEnum(CallbackData::class),
                SmileJoystick::exist($callbackQueryData) => $factory->handleEnum(SmileJoystick::class),
                default                                  => $factory->handleDefault()
            };
            dump(date('[Y-m-d H:i:s] ') . 'Unhandled private callback.');
            return;
        }
        dump(date('[Y-m-d H:i:s] ') . 'Undefined callback.');
    }

    private function unhandledType(ResultativeInterface $update)
    {
        dump($update->getResult());
    }
}
