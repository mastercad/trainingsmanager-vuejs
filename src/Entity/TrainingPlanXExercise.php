<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingPlanXExercise
 *
 * @ORM\Table(name="training_plan_x_exercise", uniqueConstraints={@ORM\UniqueConstraint(name="training_plan_exercise", columns={"exercise", "training_plan"})}, indexes={@ORM\Index(name="training_plan_x_exercise_create_user_fk", columns={"creater"}), @ORM\Index(name="training_plan_x_exercise_id", columns={"id"}), @ORM\Index(name="training_plan_x_exercise_training_plan_fk", columns={"training_plan"}), @ORM\Index(name="training_plan_x_exercise_update_user_fk", columns={"updater"}), @ORM\Index(name="IDX_B83DAAE5AEDAD51C", columns={"exercise"})})
 * @ORM\Entity
 */
class TrainingPlanXExercise
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
     * @var int
     *
     * @ORM\Column(name="exercise_order", type="integer", nullable=false, options={"default"="1","comment"="ist gedacht um die reihenfolge der übungen nachträglich noch ändern zu können"})
     */
    private $exerciseOrder = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", length=65535, nullable=false)
     */
    private $remark;

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
     *   @ORM\JoinColumn(name="creater", referencedColumnName="id")
     * })
     */
    private $creater;

    /**
     * @var \Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise", referencedColumnName="id")
     * })
     */
    private $exercise;

    /**
     * @var \TrainingPlans
     *
     * @ORM\ManyToOne(targetEntity="TrainingPlans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_plan", referencedColumnName="id")
     * })
     */
    private $trainingPlan;

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
