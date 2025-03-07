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
     * Provides access to the database system.
     */
    public Database $database;

    /**
     * Provides various helper functions.
     */
    public Utilities $utilities;

    /**
     * Creates a TalesBot client instance.
     *
     * - <multiple>: All options available on the Discord\Discord class
     * - databaseDsn: A data source name pointing to the database
     * - loggerName: A simple descriptive name attached to all log records
     *
     * @param array<string, mixed> $options An array of options for this instance
     *
     * @throws \Discord\Exceptions\IntentException
     */
    public function __construct(array $options = [])
    {
        $this->database = new Database(['databaseDsn' => $options['databaseDsn']]);
        $this->utilities = new Utilities();

        // Defaults.
        // Last checked 2025-03-05.
        // @see Discord\Discord::resolveOptions()
        $options['loggerName'] = $options['loggerName'] ?? 'TalesBot';
        $options['logger'] = $options['logger'] ?? $this->createColoredLogger($options['loggerName']);
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
     * @param string $name A descriptive name attached to all log records
     *
     * @return Logger A colored Monolog log channel to stdout
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
     * Return a list of asset types, keyed by attribute class.
     *
     *  - attribute: The attribute class the asset type must use
     *  - type: A singular label that describes the asset type
     *  - property: The property where the asset types is stored
     *
     * @return array<string, array{
     *   attribute: string,
     *   type: string,
     *   property: string,
     *  }>
     */
    public function getAssetTypes(): array
    {
        return [
            'TalesBot\Attributes\Command' => [
                'attribute' => 'TalesBot\Attributes\Command',
                'type' => 'command',
                'property' => 'commands',
            ],
            'TalesBot\Attributes\Recipe' => [
                'attribute' => 'TalesBot\Attributes\Recipe',
                'type' => 'recipe',
                'property' => 'recipes',
            ],
        ];
    }

    /**
     * Find and load asset classes.
     *
     * @param string|string[] $dirs A directory or array of directories
     */
    public function loadAssetsIn(string|array $dirs): void
    {
        // Autoload asset namespaces.
        $loader = new ClassLoader();
        $loader->addPsr4('', $dirs);
        $loader->register();

        // Load info about our asset types.
        $assetTypes = $this->getAssetTypes();

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
                    $assetName = $asset->getInfo($this)['name'];
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
