<?php

declare(strict_types=1);

namespace TalesBot;

use Discord\Builders\CommandBuilder;
use Discord\Parts\Interactions\Interaction;

/**
 * The interface all commands must implement.
 */
interface CommandInterface extends AssetInterface
{
    /**
     * Return an application command built with DiscordPHP's CommandBuilder.
     */
    public function getCommandBuilder(TalesBot $talesBot): CommandBuilder;

    /**
     * Return the command's autocomplete suggestions.
     *
     * @return \Discord\Parts\Interactions\Command\Choice[]|null
     */
    public function autocomplete(Interaction $interaction): ?array;

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void;
}
