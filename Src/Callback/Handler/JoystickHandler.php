<?php

namespace Makhnanov\TelegramSeaBattle\Callback\Handler;

use Makhnanov\TelegramSeaBattle\Callback\CallbackHandler;

class JoystickHandler extends CallbackHandler
{
    public function handle(): void
    {
        //        $text = $this->update->callback_query->message->text;
        //        $exploded = explode(PHP_EOL, $text, 12);
        //        $enemyField = new EnemyField();
        //        for ($i = 1; $i <= 10; $i++) {
        //            $enemyField->addRow(array_values(EmojiHelper::splitByEmoji($exploded[$i])));
        //        }
        //        /** @noinspection PhpParamsInspection */
        //        $moveResult = $enemyField->move($this->enum);
        //        if ($moveResult) {
        //            $this->game->getBot()->editMessageText(
        //                str_repeat('🌫', 12) . PHP_EOL . $enemyField . $exploded[11],
        //                $this->chat_id,
        //                $this->message_id,
        //                reply_markup: Snippet::inlneJoystick()
        //            );
        //        }
    }
}
