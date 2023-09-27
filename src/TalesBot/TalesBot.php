<?php

declare(strict_types=1);

namespace TalesBot;

use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Composer\Autoload\ClassLoader;
use Discord\Discord;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Finder\Finder;
use TalesBot\Commands\CommandInterface;
use TalesBot\Recipes\RecipeInterface;

/**
 * The TalesBot implementation of the Discord client class.
 */
class TalesBot extends Discord
{
    /**
     * An array of loaded commands.
     *
     * @var array<array-key, CommandInterface>
     */
    public array $commands = [];

    /**
     * An array of loaded recipes.
     *
     * @var array<array-key, RecipeInterface>
     */
    public array $recipes = [];

    /**
     * Creates a TalesBot client instance.
     *
     * - <multiple>: All options available on the Discord\Discord class.
     * - loggerName: A simple descriptive name that is attached to all log records
     *
     * @param array<string, mixed> $options An array of options for this instance
     *
     * @throws \Discord\Exceptions\IntentException
     */
    public function __construct(array $options = [])
    {
        // Defaults.
        $options['loggerName'] = $options['loggerName'] ?? 'TalesBot';
        $options['logger'] = $options['logger'] ?? $this->createColoredLogger($options['loggerName']);

        // Last updated 2023-09-23.
        // @see Discord\Discord::resolveOptions()
        $discordOptions = array_intersect_key($options, array_flip([
            'token',
            'shardId',
            'shardCount',
            'loop',
            'logger',
            'loadAllMembers',
            'disabledEvents',
            'storeMessages',
            'retrieveBans',
            'intents',
            'socket_options',
            'dnsConfig',
            'cache',
        ]));

        parent::__construct($discordOptions);
    }

    /**
     * Create a colored Monolog log channel to stdout.
     *
     * @param string $name A simple descriptive name that is attached to all log records
     *
     * @return \Monolog\Logger A colored Monolog log channel to stdout
     */
    private function createColoredLogger(string $name = ''): Logger
    {
        $logger = new Logger($name);
        $handler = new StreamHandler('php://stdout', Level::Debug);
        $handler->setFormatter(new ColoredLineFormatter());
        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * Find and load asset classes.
     *
     * @param string|string[] $dirs A directory path or an array of directories
     *
     * @throws \ReflectionException
     */
    public function loadAssetsIn(string|array $dirs): void
    {
        // Autoload asset namespaces.
        $loader = new ClassLoader();
        $loader->addPsr4('', $dirs);
        $loader->register();

        $assetTypes = [
            'TalesBot\Attributes\Command' => [
                'type' => 'command',
                'property' => 'commands',
            ],
            'TalesBot\Attributes\Recipe' => [
                'type' => 'recipe',
                'property' => 'recipes',
            ],
        ];

        $finder = new Finder();
        $finder->files()->name('*.php')->in($dirs)->sortByName();

        foreach ($finder as $file) {
            $this->getLogger()->debug('Checking if '.$file->getPathname().' is a TalesBot asset');
            $class = str_replace($dirs, '', $file->getPathname());
            $class = str_replace(['/', '.php'], ['\\', ''], $class);

            /** @var class-string $class */
            $reflector = new \ReflectionClass($class);
            foreach ($reflector->getAttributes() as $attribute) {
                $attributeName = $attribute->getName();
                if (isset($assetTypes[$attributeName])) {
                    /** @var CommandInterface $asset */
                    $asset = new $class();
                    $assetName = $asset->getInfo()['name'];
                    $assetType = $assetTypes[$attributeName]['type'];
                    $assetProperty = $assetTypes[$attributeName]['property'];

                    // Map all assets to a lookup property.
                    $this->{$assetProperty}[$assetName] = $asset;
                    $this->getLogger()->notice("Loaded $assetType '$assetName' from ".$file->getPathname());

                    // If the asset is a command, start listening.
                    if ('TalesBot\Attributes\Command' === $attributeName) {
                        $this->getLogger()->notice("Listening for $assetType '$assetName' from ".$file->getPathname());
                        $this->listenCommand($assetName, $asset->handle(...), $asset->autocomplete(...));
                    }
                }
            }
        }
    }
}
