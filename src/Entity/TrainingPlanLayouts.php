<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingPlanLayouts
 *
 * @ORM\Table(name="training_plan_layouts", uniqueConstraints={@ORM\UniqueConstraint(name="trainingsplan_layout_name", columns={"name"})}, indexes={@ORM\Index(name="training_plan_layout_create_user_fk", columns={"creator"}), @ORM\Index(name="training_plan_layout_id", columns={"id"}), @ORM\Index(name="training_plan_layout_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class TrainingPlanLayouts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator", referencedColumnName="id")
     * })
     */
    private $creator;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
