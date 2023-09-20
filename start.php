<?php

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

// Run forever.
set_time_limit(0);

// Load the .env file.
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Add ANSI color support to the logger.
$logger = new Logger('BentoTales');
$handler = new StreamHandler('php://stdout', Level::Debug);
$handler->setFormatter(new ColoredLineFormatter());
$logger->pushHandler($handler);

$discord = new Discord([
    'token' => $_ENV['BOT_TOKEN'],
    'logger' => $logger,
]);

$discord->on('init', function (Discord $discord) {
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        $discord->getLogger()->notice("{$message->author->username}: {$message->content}");
    });
});

$discord->run();
