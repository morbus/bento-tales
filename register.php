<?php

/**
 * @file
 * Register application commands for the bot.
 *
 * The Discord Developer Portal says "There is a global rate limit of 200
 * application command creates per day, per guild", and the DiscordPHP wiki
 * continues with "Commands are part of data tied to your Bot Application
 * and Guild, NOT your code. Therefore, registering commands should only be
 * done once or when the command definition needs to be updated, NOT every
 * time your bot starts and is ready."
 *
 * @see https://discord.com/developers/docs/interactions/application-commands
 * @see https://github.com/discord-php/DiscordPHP/wiki/Slash-Command
 */

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use TalesBot\TalesBot;

// Load the .env file.
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Configure the bot.
$bentoTales = new TalesBot([
    'databaseDsn' => $_ENV['BOT_DATABASE_DSN'],
    'loggerName' => 'BentoTales',
    'token' => $_ENV['BOT_TOKEN'],
]);

// Load all game assets when the bot is ready.
$bentoTales->on('init', static function (TalesBot $bentoTales) {
    $bentoTales->loadAssetsIn([
        './src',
        './contrib',
        './custom',
    ]);

    // Register commands.
    $pendingPromises = [];
    foreach ($bentoTales->commands as $command) {
        $pendingPromises[] = $bentoTales->application->commands->save(
            $bentoTales->application->commands->create(
                $command->getInfo($bentoTales)['commandBuilder']->toArray()
            )
        );
    }

    // Wait until all registrations have completed.
    \React\Promise\all($pendingPromises)->then(function ($resolved) use ($bentoTales) {
        $bentoTales->getLogger()->notice('Application commands have been registered.');
        $bentoTales->close();
    });
});

$bentoTales->run();
