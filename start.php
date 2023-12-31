<?php

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use Discord\Parts\User\Activity;
use TalesBot\TalesBot;

// Load the .env file.
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Configure the bot.
$bentoTales = new TalesBot([
    'token' => $_ENV['BOT_TOKEN'],
    'loggerName' => 'BentoTales',
]);

// Load all game assets when the bot is ready.
$bentoTales->on('init', static function (TalesBot $bentoTales) {
    $bentoTales->loadAssetsIn([
        './src',
        './custom',
    ]);

    $bentoTales->updatePresence(new Activity($bentoTales, [
        'type' => Activity::TYPE_WATCHING,
        'name' => 'for /awaken',
    ]));
});

// Run forever.
$bentoTales->run();
