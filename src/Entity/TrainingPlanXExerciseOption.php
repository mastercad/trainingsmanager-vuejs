<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingPlanXExerciseOption
 *
 * @ORM\Table(name="training_plan_x_exercise_option", uniqueConstraints={@ORM\UniqueConstraint(name="unique_training_plan_x_exercise_option_id", columns={"id"})}, indexes={@ORM\Index(name="training_plan_x_exercise_option_create_user_fk", columns={"creator"}), @ORM\Index(name="training_plan_x_exercise_option_exercise_option_fk", columns={"exercise_option"}), @ORM\Index(name="training_plan_x_exercise_option_training_plan_exercise_fk", columns={"training_plan_x_exercise"}), @ORM\Index(name="training_plan_x_exercise_option_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class TrainingPlanXExerciseOption
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
     * @ORM\Column(name="option_value", type="string", length=255, nullable=false)
     */
    private $optionValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

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
     * @var \ExerciseOptions
     *
     * @ORM\ManyToOne(targetEntity="ExerciseOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_option", referencedColumnName="id")
     * })
     */
    private $exerciseOption;

    /**
     * @var \TrainingPlanXExercise
     *
     * @ORM\ManyToOne(targetEntity="TrainingPlanXExercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_plan_x_exercise", referencedColumnName="id")
     * })
     */
    private $trainingPlanXExercise;

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
