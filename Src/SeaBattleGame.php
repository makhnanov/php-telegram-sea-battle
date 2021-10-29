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
use Makhnanov\Telegram81\Helper\Snippet;
use Makhnanov\TelegramSeaBattle\Language\English;
use Makhnanov\TelegramSeaBattle\Language\Russian;
use Makhnanov\TelegramSeaBattle\Language\Translator;
use Makhnanov\TelegramSeaBattle\Language\UnknownLanguage;
use Predis\Client;
use Yiisoft\Strings\StringHelper;

use function Makhnanov\Telegram81\callbackButton;
use function Makhnanov\Telegram81\isPrivate;

class SeaBattleGame
{
    private Bot $bot;

    private Client $redis;

    private ?Translator $translator;

    public function __construct()
    {
        //$this->bot->sendMessage(
        //    '@program_mem',
        //    '390941013',
        //    Message::$start,
        //    disable_notification: true,
        //    reply_markup: [
        //        'inline_keyboard' => [
        //            [
        //                callbackButton(ButtonText::RUSSIAN),
        //                callbackButton(ButtonText::ENGLISH)
        //            ],
        //        ]
        //    ]
        //);
        $this->bot = new B(getenv(Env::TOKEN))
            ?? throw new InvalidArgumentException('Bot token must not be empty.');
        $this->redis = new Client(['host' => getenv(Env::REDIS_HOST)]);
        echo 'Started at ' . date('Y-m-d H:i:s') . PHP_EOL;
    }

    /**
     * @throws UnknownLanguage
     */
    private function getTranslator(LangEnum $enum = null): Translator
    {
        if ($enum) {
            $this->translator = $enum === LangEnum::ENGLISH
                ? new English()
                : new Russian();

        }
        if (!$this->translator) {
            throw new UnknownLanguage();
        }
        return $this->translator;
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

            $chat_id = $update->callback_query->from->id;
            $message_id = $update->callback_query->message->message_id;

            /** @noinspection PhpExpressionResultUnusedInspection */
            match (true) {
                LangEnum::exist($callbackQueryData)      => LangEnum::tryByName($callbackQueryData),
                CallbackData::exist($callbackQueryData)  => CallbackData::tryByName($callbackQueryData),
                SmileJoystick::exist($callbackQueryData) => SmileJoystick::tryByName($callbackQueryData),
                default                                  => null
            };

            if ($case = LangEnum::tryByName($callbackQueryData)) {
                $currentUserId = $chat->id;
                $this->redis->hset('user' . $currentUserId, 'language', $callbackQueryData);
                $translator = $this->getTranslator($case);
                $this->bot->editMessageText(
                    $translator->yourLanguage(),
                    $chat_id,
                    $message_id,
                    reply_markup: InlineKeyboardMarkup::new([
                        callbackButton($translator->change(), CallbackData::CHANGE_LANGUAGE->name)
                    ])
                );
                $this->sendField($update);
                return;
            }
            if ($case = CallbackData::tryByName($callbackQueryData)) {
                match ($case) {
                    CallbackData::CHANGE_LANGUAGE => $this->bot->editMessageText(
                        Message::$changeLanguage,
                        $chat_id,
                        $message_id,
                        reply_markup: InlineKeyboardMarkup::new(Keyboard::languages())
                    ),
                };
                return;
            }
            if ($case = SmileJoystick::tryByName($callbackQueryData)) {
                $text = $update->callback_query->message->text;
                $exploded = explode(PHP_EOL, $text, 12);
                $enemyField = new EnemyField();
                for ($i = 1; $i <= 10; $i++) {
                    $enemyField->addRow(array_values(EmojiHelper::splitByEmoji($exploded[$i])));
                }
                $moveResult = $enemyField->move($case);
                dump($moveResult);
                if ($moveResult) {
                    $this->bot->editMessageText(
                        str_repeat('🌫', 12) . PHP_EOL . $enemyField . $exploded[11],
                        $chat_id,
                        $message_id,
                        reply_markup: Snippet::inlneJoystick()
                    );
                }

                return;
            }
            dump(date('[Y-m-d H:i:s] ') . 'Unhandled private callback.');
            return;
        }
        dump(date('[Y-m-d H:i:s] ') . 'Undefined callback.');
    }

    public function sendField(Update & ResultativeInterface $update): void
    {
        $chat = $update->callback_query?->message->chat;
        $this->bot->sendMessage(
            $chat->id,
            '🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫
🌫🕸🎯🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸👻🕸🎯🎯🎯🕸🕸🎯🕸🌫
🌫🕸👻🕸🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸👆🕸🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸👻🎯🎯☠️🎯🕸🕸🕸🕸🌫
🌫🕸🎯🕸🎯🎯🎯🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🎯🕸🕸🌫
🌫🕸🕸🎯🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫0️⃣1️⃣2️⃣3️⃣4️⃣5️⃣6️⃣7️⃣8️⃣9️⃣🌫
🌫🕸🕸🕸🕸🕸🚢🕸🕸🕸🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🚢🕸🌫
🌫🚢🕸🕸🚢🕸🕸🕸🕸🚢🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🚢🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🕸🕸🕸🚢🕸🕸🚢🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🚢🕸🕸🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🕸🕸🌫
🌫🚢🚢🚢🕸🕸🚢🕸🕸🚢🕸🌫
🌫🕸🕸🕸🕸🕸🕸🕸🕸🚢🕸🌫
🌫🌫🌫🌫🌫🌫🌫🌫🌫🌫',
            reply_markup: Snippet::inlneJoystick()
        );
    }

    private function unhandledType(ResultativeInterface $update)
    {
        dump($update->getResult());
    }
}
