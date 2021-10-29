<?php
declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED);

use Dotenv\Dotenv;
use Makhnanov\TelegramSeaBattle\SeaBattleGame;
use Symfony\Component\VarDumper\Caster\ReflectionCaster;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;

require_once __DIR__ . '/vendor/autoload.php';

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $cloner->addCasters(ReflectionCaster::UNSET_CLOSURE_FILE_INFO);
    $dumper = new CliDumper(
        null,
        null,
        AbstractDumper::DUMP_LIGHT_ARRAY | AbstractDumper::DUMP_TRAILING_COMMA
    );
    $dumper->dump($cloner->cloneVar($var));
});

$dotenv = Dotenv::createUnsafeImmutable(__DIR__, '.env');
$dotenv->load();

$game = new SeaBattleGame();
$game->loop();
