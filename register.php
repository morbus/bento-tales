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

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Discord\Builders\CommandBuilder;
use Discord\Discord;
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
    $commands = [
        ['name' => 'awaken', 'description' => 'Find an agreeable piece of ground and strive to strive.'],
        ['name' => 'dream', 'description' => '@todo'],
        ['name' => 'make', 'description' => '@todo'],
        ['name' => 'view', 'description' => '@todo'],
    ];

    $pendingPromises = [];
    foreach ($commands as $command) {
        $pendingPromises[] = $discord->application->commands->save(
            $discord->application->commands->create(
                CommandBuilder::new()
                ->setName($command['name'])
                ->setDescription($command['description'])
                ->toArray()
            )
        );
    }

    \React\Promise\all($pendingPromises)->then(function ($resolved) use ($discord) {
        $discord->getLogger()->notice('Application commands have been registered.');
        $discord->close();
    });
});

$discord->run();
