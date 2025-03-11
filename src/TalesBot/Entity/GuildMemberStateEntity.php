<?php

declare(strict_types=1);

namespace TalesBot\Entity;

use Doctrine\ORM\Mapping as ORM;
use TalesBot\Attribute\Entity;
use TalesBot\EntityInterface;

/**
 * Store game state for a guild member.
 */
#[Entity]
#[ORM\Entity]
#[ORM\Table(name: 'guild_member_state')]
class GuildMemberStateEntity implements EntityInterface
{
    /**
     * Unique identifier and primary key.
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * The unique ID of the Discord guild.
     */
    #[ORM\Column(name: 'guild_id', type: 'string')]
    private string $guildId;

    /**
     * The unique ID of the Discord user.
     */
    #[ORM\Column(name: 'user_id', type: 'string')]
    private string $userId;

    /**
     * The primary type of state being tracked. (e.g., awaken, recipe, lastSeen).
     */
    #[ORM\Column(type: 'string')]
    private string $type;

    /**
     * The subtype of state being tracked. (e.g., tutorial, eggAndRice, village).
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private string $subtype;

    /**
     * The subsubtype of state being tracked. (e.g., count, exquisite, graveyard).
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private string $subsubtype;

    /**
     * The boolean value of the state being stored (e.g., 0, 1).
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private string $boolean;

    /**
     * The float value of the state being stored (e.g., 5, -1.234, 1000000).
     */
    #[ORM\Column(type: 'decimal', precision: 2, nullable: true)]
    private string $decimal;

    /**
     * The string value of the state being stored (e.g., grocery store, bento shop).
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private string $string;

    /**
     * The datetime value of the state being stored (e.g. "2025--03-10 10:27:55.666").
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private string $datetime;

    /**
     * The JSON value of the state being stored (e.g. {"complex": "values", "are": "here"}).
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private string $json;

    public function update(): void
    {
    }
}
