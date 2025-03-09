<?php

/**
 * @file
 * Start the bot.
 */

declare(strict_types=1);

include __DIR__.'/vendor/autoload.php';

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Discord\Parts\User\Activity;
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

// Load all addons when the bot is ready.
$talesBot->on('init', static function (TalesBot $talesBot) {
    $talesBot->loadAddons();
    $talesBot->updatePresence(new Activity($talesBot, [
        'type' => Activity::TYPE_WATCHING,
        'name' => 'for /awaken',
    ]));
});

$talesBot->run();
