<?php

declare(strict_types=1);

namespace TalesBot\Addons\Reflect;

use Doctrine\ORM\Mapping as ORM;
use TalesBot\Attributes\Entity;
use TalesBot\EntityInterface;

#[Entity]
#[ORM\Entity]
#[ORM\Table(name: 'state')]
class GuildMemberStateEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $name;
}
