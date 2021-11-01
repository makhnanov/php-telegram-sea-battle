<?php

namespace Makhnanov\TelegramSeaBattle;

use InvalidArgumentException;
use Makhnanov\Telegram81\Api\B;
use Makhnanov\Telegram81\Api\Bot;
use Makhnanov\Telegram81\Api\Enumeration\AllowedUpdates;
use Makhnanov\Telegram81\Api\Exception\UnchangedMessageException;
use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Emoji\Enumeration\JoystickEnum;
use Makhnanov\Telegram81\Helper\ResultativeInterface;
use Makhnanov\Telegram81\Snippet\KeyboardSnippet;
use Makhnanov\Telegram81\Snippet\LanguageSnippetEnum;
use Makhnanov\TelegramSeaBattle\Callback\HandlerFactory;
use Predis\Client;
use Yiisoft\Strings\StringHelper;

use function Makhnanov\Telegram81\isPrivate;

class SeaBattleGame
{
    private Bot $bot;

    private Client $redis;

    public function __construct()
    {
        Logger::log('Start');
        $this->bot = new B(
            getenv(Env::TOKEN) ?: throw new InvalidArgumentException('Bot token must not be empty.')
        );
        $this->redis = new Client([
            'host' => getenv(Env::REDIS_HOST) ?: throw new InvalidArgumentException('Redis host must not be empty.')
        ]);
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
                (bool)$callbackQueryData => $this->handleCallbackDataQuery($update),
                $this->isStart($text) => $this->start($update),
                default => $this->unhandledType($update),
            };
        } catch (UnchangedMessageException) {
            Logger::log('Unchanged message exception.');
        } catch (\Throwable $e) {
            //            dump($e->getResponse()->getBody()->getContents());
            throw $e;
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
                reply_markup: KeyboardSnippet::russianEnglish()
            );
        }
    }

    public function handleCallbackDataQuery(Update & ResultativeInterface $update): void
    {
        $chat = $update->callback_query?->message->chat;
        $callbackQueryData = $update->callback_query->data;
        if (isPrivate($chat)) {
            Logger::log('Private callback.');
            $factory = new HandlerFactory($this, $update);
            match (true) {
                LanguageSnippetEnum::exist($callbackQueryData) => $factory->handleEnum(LanguageSnippetEnum::class),
                CallbackData::exist($callbackQueryData) => $factory->handleEnum(CallbackData::class),
                JoystickEnum::exist($callbackQueryData) => $factory->handleEnum(JoystickEnum::class),
                Skin::exist($callbackQueryData) => $factory->handleEnum(Skin::class),
                default => $factory->handleDefault()
            };
            return;
        }
        Logger::log('Not private callback received.');
    }

    private function unhandledType(ResultativeInterface $update)
    {
        Logger::log($update->getResult());
    }
}
