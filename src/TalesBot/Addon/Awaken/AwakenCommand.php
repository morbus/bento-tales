<?php

declare(strict_types=1);

namespace TalesBot\Addon\Awaken;

use DateTime;
use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use TalesBot\Attribute\Command;
use TalesBot\CommandInterface;
use TalesBot\Entity\GuildMemberStateEntity;
use TalesBot\TalesBot;
use TalesBot\Utilities;

/**
 * Find an agreeable piece of ground and strive to strive.
 *
 * @see https://suttacentral.net/mn26/en/bodhi
 * @see https://en.wikipedia.org/wiki/108_(number)
 * @see https://uncannyjapan.com/podcast/musha-burui/
 */
#[Command]
class AwakenCommand implements CommandInterface
{
    public function getCommandBuilder(TalesBot $talesBot): CommandBuilder
    {
        return CommandBuilder::new()
            ->setName('awaken')
            ->setDescription('Find an agreeable piece of ground and strive to strive.');
    }

    public function autocomplete(Interaction $interaction): ?array
    {
        return null;
    }

    public function handle(Interaction $interaction): void
    {
        $this->newGame($interaction);
    }

    /**
     * Start the game for a new player.
     */
    public function newGame(Interaction $interaction): void
    {
        /** @var TalesBot $talesBot */
        $talesBot = $interaction->getDiscord();
        $utilities = new Utilities();

        $guildMemberState = new GuildMemberStateEntity([
            'guildId' => $interaction->guild_id,
            'userId' => $interaction->user->id,
            'type'  => 'awaken',
            'subtype' => 'tutorial',
            'subsubtype' => 'daysSeen',
            'int' => 1,
            'datetime' => new DateTime('now')
        ]);
        $talesBot->entityManager->persist($guildMemberState);
        $talesBot->entityManager->flush();

        $embed = new Embed($talesBot);
        $embed
            ->setColor('#7F9D61')
            ->setTitle('The sun rises and heralds a new day')
            ->setThumbnail('https://github.com/morbus/bento-tales/raw/main/src/TalesBot/Commands/Awaken/media/awaken--lorc--sunrise.png')
            ->setDescription(
                $utilities->oneLine('
                    100 days... 1,000 days... 10,000 days and 800 more... For nearly 30 years, you\'ve worked at your
                    bento shop in a small East Asia town, serving health to a small but treasured community. It is
                    your life\'s work, and the happiness of a belly well sated has been enough to give you comfort.
                ')
                ."\n\n".
                $utilities->oneLine('
                    The sun continues its ascension and your first customers will be here soon. It is a morning like
                    any other but, as you prep your assembly area, something feels... different. You are almost nervous
                    with anticipation, as if *musha burui* (武者震い) has taken hold, as if you shake like a samurai
                    before a great battle. Odd.
                ')
                ."\n\n".
                $utilities->oneLine('
                    *To continue, type `/make Egg and Rice`.*
                ')
            )
        ;

        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($embed));
    }
}
