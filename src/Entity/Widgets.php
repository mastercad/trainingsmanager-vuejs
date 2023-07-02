<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Widgets
 */
#[ORM\Table(name: 'widgets')]
#[ORM\Index(name: 'widget_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'widget_id', columns: ['id'])]
#[ORM\Index(name: 'widget_update_user_fk', columns: ['updater'])]
#[ORM\Entity]
class Widgets
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 50, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'editable', type: 'boolean', nullable: false)]
    private bool $editable = false;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;
}
