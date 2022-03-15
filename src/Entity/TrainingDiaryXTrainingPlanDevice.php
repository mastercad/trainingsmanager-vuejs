<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXTrainingPlanDevice
 *
 * @ORM\Table(name="training_diary_x_training_plan_device", indexes={@ORM\Index(name="index_training_diary_x_training_plan_exercise_training_diary_fk", columns={"training_diary"}), @ORM\Index(name="training_diary_x_training_plan_exercise_create_user_fk", columns={"creator"}), @ORM\Index(name="training_diary_x_training_plan_exercise_id", columns={"id"}), @ORM\Index(name="training_diary_x_training_plan_exercise_t_p_x_e_fk", columns={"training_plan_x_device"}), @ORM\Index(name="training_diary_x_training_plan_exercise_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class TrainingDiaryXTrainingPlanDevice
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
     * @ORM\Column(name="training_plan_x_device", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $trainingPlanXDevice;

    /**
     * @var int
     *
     * @ORM\Column(name="training_diary", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $trainingDiary;

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
     * @var int
     *
     * @ORM\Column(name="creator", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $creator;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var int|null
     *
     * @ORM\Column(name="updater", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $updater;


}
