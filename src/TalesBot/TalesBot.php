<?php

declare(strict_types=1);

namespace TalesBot;

use Discord\Discord;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Finder\Finder;

/**
 * The TalesBot implementation of the Discord client class.
 */
class TalesBot extends Discord
{
    /**
     * Directories where addons are located.
     *
     * @var string[]
     */
    public array $addonDirs = [];

    /**
     * Known assets sorted by their attribute.
     *
     * @var array<0|class-string, array<0|class-string, ?object>>
     */
    public array $assets = [];

    /**
     * The Doctrine ORM entity manager.
     */
    public EntityManagerInterface $entityManager;

    /**
     * Creates a TalesBot client instance.
     *
     * - <multiple>: All options available on the Discord\Discord class
     * - addonDirs: An array of directories where addons are located
     * - doctrine: The configured Doctrine ORM entity manager.
     *
     * @param array<string, mixed> $options An array of options
     *
     * @throws \Discord\Exceptions\IntentException
     */
    public function __construct(array $options = [])
    {
        $this->addonDirs = $options['addonDirs'] ?? [];
        $this->entityManager = $options['doctrine'];

        // Defaults.
        // Last checked 2025-03-05.
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
     * Find asset classes in addon directories and store in $this->assets.
     *
     * @param class-string[] $attributes A list of attributes to find assets of
     */
    public function findAssets(array $attributes = []): void
    {
        // Default TalesBot attributes.
        $attributes = $attributes ?: [
            'TalesBot\Attribute\Command',
            'TalesBot\Attribute\Entity',
            'TalesBot\Attribute\Recipe',
        ];

        // Search addon dirs.
        $finder = new Finder();
        $finder->files()->name('*.php')->in($this->addonDirs)->sortByName();

        // Turn addon files into a class name, then check the attributes.
        // If a class uses the desired attributes, store it in $this->assets.
        foreach ($finder as $file) {
            $this->getLogger()->debug('Checking if '.$file->getRealPath().' is a requested TalesBot asset');
            $assetClass = str_replace($this->addonDirs, '', $file->getPathname());
            $assetClass = str_replace(['/', '.php'], ['\\', ''], $assetClass);

            /** @var class-string $assetClass */
            $reflector = new \ReflectionClass($assetClass);
            $assetAttributes = $reflector->getAttributes();
            foreach ($assetAttributes as $assetAttribute) {
                if (\in_array($assetAttribute->getName(), $attributes, true)) {
                    $this->getLogger()->info($file->getRealPath().' uses attribute '.$assetAttribute->getName());
                    $this->assets[$assetAttribute->getName()][$assetClass] = null;
                }
            }
        }
    }

    /**
     * Find and load assets in our addon directories.
     */
    public function loadAddons(): void
    {
        $this->findAssets();
        $this->loadCommandAssets();
        $this->loadEntityAssets();
        $this->loadRecipeAssets();
    }

    /**
     * Load command assets.
     */
    public function loadCommandAssets(): void
    {
        foreach (array_keys($this->assets['TalesBot\Attribute\Command']) as $commandClass) {
            $command = new $commandClass();
            $commandBuilder = $command->getCommandBuilder($this)->toArray();
            $this->listenCommand($commandBuilder['name'], $command->handle(...), $command->autocomplete(...));
            $this->getLogger()->notice('Listening for command /'.$commandBuilder['name'].' from '.$commandClass);
        }
    }

    /**
     * Load entity assets.
     */
    public function loadEntityAssets(): void
    {
        // Update the database with any schema changes.
        // FIXME: This will surely crash and burn one day.
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($classes);

        foreach (array_keys($this->assets['TalesBot\Attribute\Entity']) as $entityClass) {
            $this->getLogger()->notice('Updated entity schema from '.$entityClass);
        }
    }

    /**
     * Load recipe assets.
     */
    public function loadRecipeAssets(): void
    {
        // Preload recipes as they are queried a lot with various commands.
        foreach (array_keys($this->assets['TalesBot\Attribute\Recipe']) as $recipeClass) {
            $this->assets['TalesBot\Attribute\Recipe'][$recipeClass] = new $recipeClass();
            $this->getLogger()->notice('Loaded recipe from '.$recipeClass);
        }
    }
}
