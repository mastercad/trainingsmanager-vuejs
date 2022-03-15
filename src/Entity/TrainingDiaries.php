<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaries
 *
 * @ORM\Table(name="training_diaries", indexes={@ORM\Index(name="index_training_diary_create_user_fk", columns={"creator"}), @ORM\Index(name="index_training_diary_training_plan_x_exercise_fk", columns={"training_plan_x_exercise"}), @ORM\Index(name="index_training_diary_update_user_fk", columns={"updater"}), @ORM\Index(name="training_diary_id", columns={"id"})})
 * @ORM\Entity
 */
class TrainingDiaries
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
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;

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
     * @var \Exercises
     *
     * @ORM\ManyToOne(targetEntity="Exercises")
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
