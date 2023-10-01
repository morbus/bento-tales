<?php

declare(strict_types=1);

namespace TalesBot\Commands;

use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attributes\Command;
use TalesBot\TalesBot;

/**
 * Find an agreeable piece of ground and strive to strive.
 *
 * @see https://suttacentral.net/mn26/en/bodhi
 * @see https://en.wikipedia.org/wiki/108_(number)
 * @see https://uncannyjapan.com/podcast/musha-burui/
 */
#[Command]
class Awaken implements CommandInterface
{
    /**
     * Return information about the command.
     */
    public function getInfo(TalesBot $talesBot): array
    {
        $info = [];
        $info['name'] = 'awaken';
        $info['description'] = 'Find an agreeable piece of ground and strive to strive.';
        $info['commandBuilder'] =
            CommandBuilder::new()
                ->setName($info['name'])
                ->setDescription($info['description']);

        return $info;
    }

    /**
     * Return the command's autocomplete suggestions.
     */
    public function autocomplete(Interaction $interaction): array|null
    {
        return null;
    }

    /**
     * Handle the command and optionally respond to the user.
     */
    public function handle(Interaction $interaction): void
    {
        $this->newGame($interaction);
    }

    /**
     * Start the game for a new player.
     */
    public function newGame(Interaction $interaction): void
    {
        /** @var \TalesBot\TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();

        $embed = new Embed($talesBot);
        $embed
            ->setColor('#7F9D61')
            ->setTitle('The sun rises and heralds a new day')
            ->setThumbnail('https://github.com/morbus/bento-tales/raw/main/media/awaken/awaken--lorc--sunrise.png')
            ->setDescription(
                $talesBot->utilities->oneLine('
                    100 days... 1,000 days... 10,000 days and 800 more... For nearly 30 years, you\'ve worked at your
                    bento shop in a small East Asia town, serving health to a small but treasured community. It is
                    your life\'s work, and the happiness of a belly well sated has been enough to give you comfort.
                ')
                ."\n\n".
                $talesBot->utilities->oneLine('
                    The sun continues its ascension and your first customers will be here soon. It is a morning like
                    any other but, as you prep your assembly area, something feels... different. You are almost nervous
                    with anticipation, as if *musha burui* (武者震い) has taken hold, as if you shake like a samurai
                    before a great battle. Odd.
                ')
                ."\n\n".
                $talesBot->utilities->oneLine('
                    *To continue, type `/make Egg and Rice`.*
                ')
            )
        ;

        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
    }
}
