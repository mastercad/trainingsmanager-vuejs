<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingPlans
 *
 * @ORM\Table(name="training_plans", indexes={@ORM\Index(name="training_plan_create_user_fk", columns={"creator"}), @ORM\Index(name="training_plan_id", columns={"id"}), @ORM\Index(name="training_plan_parent_fk", columns={"parent"}), @ORM\Index(name="training_plan_training_plan_layout_fk", columns={"training_plan_layout"}), @ORM\Index(name="training_plan_update_user_fk", columns={"updater"}), @ORM\Index(name="training_plan_user_fk", columns={"user"})})
 * @ORM\Entity
 */
class TrainingPlans
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
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"comment"="trainingsplan aktiv? damit nur der aktuellste angezeigt wird im tagebuch zum training"})
     */
    private $active = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="order", type="boolean", nullable=false, options={"default"="1","comment"="ist für splitpläne gedacht um die reihenfolge zu beeinflussen"})
     */
    private $order = true;

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
     * @var \TrainingPlans
     *
     * @ORM\ManyToOne(targetEntity="TrainingPlans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var \TrainingPlanLayouts
     *
     * @ORM\ManyToOne(targetEntity="TrainingPlanLayouts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_plan_layout", referencedColumnName="id")
     * })
     */
    private $trainingPlanLayout;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;


}
