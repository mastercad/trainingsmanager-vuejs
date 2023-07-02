<?php

declare(strict_types=1);

namespace App\Entity;

use Dashboards;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Users;
use Widgets;

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
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'widget_type', type: 'string', length: 250, nullable: false)]
    private string $widgetType;

    #[ORM\Column(name: 'order', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $order;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created = 'CURRENT_TIMESTAMP';

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'dashboard', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Dashboards')]
    private Dashboards $dashboard;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;

    #[ORM\JoinColumn(name: 'widget', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Widgets')]
    private Widgets $widget;
}
