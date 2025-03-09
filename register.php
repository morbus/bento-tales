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
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use TalesBot\TalesBot;

// Load the .env file.
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Addons.
$addonDirs = [
    './src',
    './contrib',
    './custom',
];

// Setup a colored Monolog logger.
$handler = new StreamHandler('php://stdout');
$handler->setFormatter(new ColoredLineFormatter());
$logger = new Logger('BentoTales');
$logger->pushHandler($handler);

// Setup the Doctrine ORM.
$dsnParser = new DsnParser();
$dsnParsed = $dsnParser->parse($_ENV['BOT_DATABASE_DSN']);
$config = ORMSetup::createAttributeMetadataConfiguration(paths: $addonDirs);
$connection = DriverManager::getConnection($dsnParsed, $config);
$entityManager = new EntityManager($connection, $config);

// Configure the bot.
$talesBot = new TalesBot([
    'addonDirs' => $addonDirs,
    'doctrine' => $entityManager,
    'logger' => $logger,
    'token' => $_ENV['BOT_TOKEN'],
]);

// Register all addon commands when the bot is ready.
$talesBot->on('init', static function (TalesBot $talesBot) {
    $talesBot->findAssets(['TalesBot\Attributes\Command']);
    $talesBot->loadCommandAssets();

    // Register commands.
    $pendingPromises = [];

    /** @var \TalesBot\CommandInterface $command */
    foreach ($talesBot->assets['TalesBot\Attributes\Command'] as $command) {
        $pendingPromises[] = $talesBot->application->commands->save(
            $talesBot->application->commands->create(
                $command->getCommandBuilder($talesBot)->toArray()
            )
        );
    }

    // Wait until all registrations have completed.
    \React\Promise\all($pendingPromises)->then(function ($resolved) use ($talesBot) {
        $talesBot->getLogger()->notice('Application commands have been registered');
        $talesBot->close();
    });
});

$talesBot->run();
