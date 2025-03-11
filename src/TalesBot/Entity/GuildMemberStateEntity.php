<?php

declare(strict_types=1);

namespace TalesBot\Entity;

use DateTime;
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
    private ?string $subtype;

    /**
     * The subsubtype of state being tracked. (e.g., count, exquisite, graveyard).
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $subsubtype;

    /**
     * The boolean value of the state being stored (e.g., 0, 1).
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $boolean;

    /**
     * The int value of the state being stored (e.g., 5, -128, 1000000).
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $int;

    /**
     * The float value of the state being stored (e.g., 42.12, -1.9721, 100.00014).
     */
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $float;

    /**
     * The string value of the state being stored (e.g., grocery store, bento shop).
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $string;

    /**
     * The datetime value of the state being stored (e.g. "2025--03-10 10:27:55.666").
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $datetime;

    /**
     * The JSON value of the state being stored (e.g. {"complex": "values", "are": "here"}).
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $json;

    // TODO document me
    public function __construct(array $values = [])
    {
        $this->guildId = $values['guildId'];
        $this->userId = $values['userId'];
        $this->type = $values['type'];
        $this->subtype = $values['subtype'] ?? null;
        $this->subsubtype = $values['subsubtype'] ?? null;
        $this->boolean = $values['boolean'] ?? null;
        $this->int = $values['int'] ?? null;
        $this->float = $values['float'] ?? null;
        $this->string = $values['string'] ?? null;
        $this->datetime = $values['datetime'] ?? null;
        $this->json = $values['json'] ?? null;
    }
}
