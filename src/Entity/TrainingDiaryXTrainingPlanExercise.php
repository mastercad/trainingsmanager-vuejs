<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXTrainingPlanExercise
 *
 * @ORM\Table(name="training_diary_x_training_plan_exercise", indexes={@ORM\Index(name="index_training_diary_x_training_plan_exercise_training_diary_fk", columns={"training_diary"}), @ORM\Index(name="training_diary_x_training_plan_exercise_create_user_fk", columns={"creator"}), @ORM\Index(name="training_diary_x_training_plan_exercise_id", columns={"id"}), @ORM\Index(name="training_diary_x_training_plan_exercise_t_p_x_e_fk", columns={"training_plan_x_exercise"}), @ORM\Index(name="training_diary_x_training_plan_exercise_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class TrainingDiaryXTrainingPlanExercise
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
     * @ORM\Column(name="comment", type="string", length=255, nullable=false)
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="flag_finished", type="boolean", nullable=false)
     */
    private $flagFinished = '0';

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
     * @var \TrainingDiaries
     *
     * @ORM\ManyToOne(targetEntity="TrainingDiaries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_diary", referencedColumnName="id")
     * })
     */
    private $trainingDiary;

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
