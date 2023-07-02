<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dashboards
 */
#[ORM\Table(name: 'dashboards')]
#[ORM\Index(name: 'dashboard_creator', columns: ['creator'])]
#[ORM\Index(name: 'dashboard_id', columns: ['id'])]
#[ORM\Index(name: 'dashboard_updater', columns: ['updater'])]
#[ORM\Index(name: 'dashboard_user', columns: ['user'])]
#[ORM\Entity]
class Dashboards
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 50, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'flag_active', type: 'boolean', nullable: false)]
    private bool $flagActive = '0';

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;

    #[ORM\JoinColumn(name: 'user', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $user;
}
