<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Makhnanov\TelegramSeaBattle;

use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Helper\Smile\SmileJoystick;
use Stringable;
use UnhandledMatchError;

class EnemyField implements Stringable
{
    private array $field;

    private int $rowNum;
    private int $colNum;

    private const FINGER = '👆';
    private const FOG = '🌫';

    #[Pure]
    public static function new()
    {
        return new self();
    }

    public function addRow(array $row)
    {
        $this->field[] = $row;
    }

    public function move(SmileJoystick $vector): bool
    {
        foreach ($this->field as $y => $row) {
            foreach ($row as $x => $column) {
                if ($column === self::FINGER) {
                    $this->rowNum = $y;
                    $this->colNum = $x;
                    break;
                }
            }
        }

        if (!isset($this->rowNum, $this->colNum)) {
            return false;
        }
        try {
            return match ($vector) {
                SmileJoystick::upLeft    => $this->softSwap(-1, -1),
                SmileJoystick::up        => $this->softSwap(-1),
                SmileJoystick::upRight   => $this->softSwap(-1, 1),
                SmileJoystick::left      => $this->softSwap(newColNum: -1),
                SmileJoystick::right     => $this->softSwap(newColNum: 1),
                SmileJoystick::downLeft  => $this->softSwap(1, -1),
                SmileJoystick::down      => $this->softSwap(1),
                SmileJoystick::downRight => $this->softSwap(1, 1),
                SmileJoystick::center    => false,
            };
        } catch (UnhandledMatchError) {
            // Silent - gold =)
        }
        return false;
    }

    public function softSwap(int $newRowNum = 0, int $newColNum = 0): bool
    {
        do {
            $newRowNum = $newRowNum + ($vectorRow ?? 0);
            $newColNum = $newColNum + ($vectorNum ?? 0);
            if (!isset($this->field[$this->rowNum + $newRowNum][$this->colNum + $newColNum])) {
                return false;
            }
            if ($this->field[$this->rowNum + $newRowNum][$this->colNum + $newColNum] === '🕸') {
                $tmp = $this->field[$this->rowNum + $newRowNum][$this->colNum + $newColNum];
                $this->field[$this->rowNum + $newRowNum][$this->colNum + $newColNum] = self::FINGER;
                $this->field[$this->rowNum][$this->colNum] = $tmp;
                return true;
            }
            !isset($vectorRow) and $vectorRow = $newRowNum;
            !isset($vectorNum) and $vectorNum = $newColNum;
        } while (true);
    }

    public function __toString(): string
    {
        $return = '';
        foreach ($this->field as $row) {
            $return .= self::FOG . implode('', $row) . self::FOG . PHP_EOL;
        }
        return $return;
    }
}
