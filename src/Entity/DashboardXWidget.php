<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
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
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'widget_type', type: 'string', length: 250, nullable: false)]
    private string $widgetType;

    #[ORM\Column(name: 'order', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $order;

    #[ORM\JoinColumn(name: 'dashboard', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Dashboards')]
    private Dashboards $dashboard;

    #[ORM\JoinColumn(name: 'widget', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Widgets')]
    private Widgets $widget;

    #[ORM\JoinColumn(name: 'creator', nullable: false, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $creator = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\JoinColumn(name: 'updater', nullable: true, referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users|null $updater = null;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of widgetType
     */
    public function getWidgetType(): string
    {
        return $this->widgetType;
    }

    /**
     * Set the value of widgetType
     */
    public function setWidgetType(string $widgetType): self
    {
        $this->widgetType = $widgetType;

        return $this;
    }

    /**
     * Get the value of order
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Set the value of order
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of dashboard
     */
    public function getDashboard(): Dashboards
    {
        return $this->dashboard;
    }

    /**
     * Set the value of dashboard
     */
    public function setDashboard(Dashboards $dashboard): self
    {
        $this->dashboard = $dashboard;

        return $this;
    }

    /**
     * Get the value of widget
     */
    public function getWidget(): Widgets
    {
        return $this->widget;
    }

    /**
     * Set the value of widget
     */
    public function setWidget(Widgets $widget): self
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * Get the value of creator
     */
    public function getCreator(): Users|null
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     */
    public function setCreator(Users $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of created
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set the value of created
     */
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updater
     */
    public function getUpdater(): Users|null
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     */
    public function setUpdater(Users|null $updater): self
    {
        $this->updater = $updater;

        return $this;
    }

    /**
     * Get the value of updated
     */
    public function getUpdated(): DateTime|null
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     */
    public function setUpdated(DateTime|null $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
