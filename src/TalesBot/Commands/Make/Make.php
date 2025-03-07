<?php

declare(strict_types=1);

namespace TalesBot\Commands\Make;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Command\Choice;
use Discord\Parts\Interactions\Command\Option;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attributes\Command;
use TalesBot\Commands\CommandInterface;
use TalesBot\TalesBot;

/**
 * Make good food and hear good stories.
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
        $info['description'] = 'Make good food and hear good stories.';
        $info['commandBuilder'] =
            CommandBuilder::new()
                ->setName($info['name'])
                ->setDescription($info['description'])
                ->addOption(
                    (new Option($talesBot))
                        ->setName('recipe')
                        ->setDescription('The recipe to prepare')
                        ->setType(Option::STRING)
                        ->setAutoComplete(true)
                        ->setRequired(true)
                );

        return $info;
    }

    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): ?array
    {
        /** @var TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();

        $choices = [];
        foreach ($talesBot->recipes as $recipe) {
            $name = $recipe->getInfo($talesBot)['name'];
            $choices[] = new Choice($talesBot, ['name' => $name, 'value' => $name]);
        }

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
        // @var \Discord\Helpers\CollectionInterface $options
        // $options = $interaction->data->options;
        // $option = $options->pull('recipe');
        // $value = $option->value;
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
