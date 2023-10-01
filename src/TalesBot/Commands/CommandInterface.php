<?php

declare(strict_types=1);

namespace TalesBot\Commands;

use Discord\Parts\Interactions\Interaction;
use TalesBot\AssetInterface;
use TalesBot\TalesBot;

/**
 * The interface all commands must implement.
 */
interface CommandInterface extends AssetInterface
{
    /**
     * Return information about the asset.
     *
     * - name: The name of the asset.
     * - description: The description of the asset.
     * - commandBuilder: A CommandBuilder class to register.
     *
     * @return array{
     *   name: string,
     *   description: string,
     *   commandBuilder: \Discord\Builders\CommandBuilder
     * }
     */
    public function getInfo(TalesBot $talesBot): array;

    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): void;

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void;
}
