<?php

declare(strict_types=1);

namespace TalesBot\Addon\Make;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Command\Choice;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attribute\Command;
use TalesBot\CommandInterface;
use TalesBot\TalesBot;

/**
 * Make good food and hear good stories.
 */
#[Command]
class MakeCommand implements CommandInterface
{
    public function getCommandBuilder(TalesBot $talesBot): CommandBuilder
    {
        return CommandBuilder::new()
            ->setName('make')
            ->setDescription('Make good food and hear good stories.')
            ->addOption(
                (new Option($talesBot))
                    ->setName('recipe')
                    ->setDescription('The recipe to prepare')
                    ->setType(Option::STRING)
                    ->setAutoComplete(true)
                    ->setRequired(true)
            );
    }

    public function autocomplete(Interaction $interaction): ?array
    {
        /** @var TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();

        $choices = [];
        // @todo Redo once we figure out how to refactor getInfo().
        //  foreach ($talesBot->assets['TalesBot\Attributes\Recipe'] as $recipe) {
        //    $name = $recipe->getInfo($talesBot)['name'];
        //    $choices[] = new Choice($talesBot, ['name' => $name, 'value' => $name]);
        //  }

        return $choices;
    }

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void
    {
        /** @var TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();

        $value = '';
        // @todo All of this gives a PHPStan warning.
        // @var \Discord\Helpers\CollectionInterface $options
        // $options = $interaction->data->options;
        // $option = $options->pull('recipe');
        // $value = $option->value;
        // FIX HERE? https://github.com/discord-php/DiscordPHP/wiki/Option_commands
        //   $content = $interaction->data->options->offsetGet('text')->value;
        if (isset($interaction->data->options['recipe'])) {
            $value = $interaction->data->options['recipe']['value'];
        }

        $embed = new Embed($talesBot);
        $embed
            ->setColor('#F6CC6C')
            ->setTitle('@todo')
            ->setDescription('got recipe '.$value)
        ;

        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
    }
}
