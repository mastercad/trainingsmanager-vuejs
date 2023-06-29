<?php

namespace App\Entity;

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
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 50, nullable: false)]
    private $name;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'flag_active', type: 'boolean', nullable: false)]
    private $flagActive = '0';

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private $updated;

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $creator;

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $updater;

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'user', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $user;


}
