<?php

declare(strict_types=1);

namespace TalesBot\Commands;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attributes\Command;
use TalesBot\TalesBot;

/**
 * @todo
 */
#[Command]
class Make implements CommandInterface
{
    /**
     * Return information about the command.
     */
    public function getInfo(TalesBot $talesBot): array
    {
        $info = [];
        $info['name'] = 'make';
        $info['description'] = '@todo';
        $info['commandBuilder'] =
            CommandBuilder::new()
                ->setName($info['name'])
                ->setDescription($info['description'])
                ->addOption(
                    (new Option($talesBot))
                        ->setName('recipe')
                        ->setDescription('The recipe to make')
                        ->setType(Option::STRING)
                        ->setAutoComplete(true)
                        ->setRequired(true)
                );

        return $info;
    }

    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): void
    {
        $interaction->getDiscord()->getLogger()->warning('inside autocomplete');
    }

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void
    {
        /** @var \TalesBot\TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();

        $embed = new Embed($interaction->getDiscord());
        $embed
            ->setColor('#F6CC6C')
            ->setTitle('@todo')
        ;

        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
    }
}
