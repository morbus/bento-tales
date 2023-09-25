<?php

declare(strict_types=1);

namespace TalesBot\Commands;

use Discord\Parts\Interactions\Interaction;
use TalesBot\AssetInterface;

/**
 * The interface all commands must implement.
 */
interface CommandInterface extends AssetInterface
{
    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): void;

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void;
}
