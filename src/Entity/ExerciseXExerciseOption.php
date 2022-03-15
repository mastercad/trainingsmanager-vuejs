<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXExerciseOption
 *
 * @ORM\Table(name="exercise_x_exercise_option", uniqueConstraints={@ORM\UniqueConstraint(name="unique_uebung_x_uebung_option_id", columns={"id"})}, indexes={@ORM\Index(name="exercise_x_exercise_option_creator", columns={"creator"}), @ORM\Index(name="exercise_x_exercise_option_exercise", columns={"exercise"}), @ORM\Index(name="exercise_x_exercise_option_exercise_option", columns={"exercise_option"}), @ORM\Index(name="exercise_x_exercise_option_updater", columns={"updater"})})
 * @ORM\Entity
 */
class ExerciseXExerciseOption
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
     * @ORM\Column(name="exercise_option_value", type="string", length=255, nullable=false)
     */
    private $exerciseOptionValue;

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
     *   @ORM\JoinColumn(name="exercise", referencedColumnName="id")
     * })
     */
    private $exercise;

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
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updater", referencedColumnName="id")
     * })
     */
    private $updater;


}
