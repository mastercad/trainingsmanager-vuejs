<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DashboardXWidget
 */
#[ORM\Table(name: 'dashboard_x_widget')]
#[ORM\Index(name: 'dashboard_x_widget_creator', columns: ['creator'])]
#[ORM\Index(name: 'dashboard_x_widget_dashboard', columns: ['dashboard'])]
#[ORM\Index(name: 'dashboard_x_widget_id', columns: ['id'])]
#[ORM\Index(name: 'dashboard_x_widget_updater', columns: ['updater'])]
#[ORM\Index(name: 'dashboard_x_widget_widget', columns: ['widget'])]
#[ORM\Entity]
class DashboardXWidget
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
    #[ORM\Column(name: 'widget_type', type: 'string', length: 250, nullable: false)]
    private $widgetType;

    /**
     * @var int
     */
    #[ORM\Column(name: 'order', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private $order;

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
     * @var \Users
     */
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $creator;

    /**
     * @var \Dashboards
     */
    #[ORM\JoinColumn(name: 'dashboard', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Dashboards')]
    private $dashboard;

    /**
     * @var \Users
     */
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $updater;

    /**
     * @var \Widgets
     */
    #[ORM\JoinColumn(name: 'widget', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Widgets')]
    private $widget;


}
