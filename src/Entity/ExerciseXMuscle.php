<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseXMuscle
 */
#[ORM\Table(name: 'exercise_x_muscle')]
#[ORM\Index(name: 'exercise_x_muscle_creator', columns: ['creator'])]
#[ORM\Index(name: 'exercise_x_muscle_exercise', columns: ['exercise'])]
#[ORM\Index(name: 'exercise_x_muscle_id', columns: ['id'])]
#[ORM\Index(name: 'exercise_x_muscle_muscle', columns: ['muscle'])]
#[ORM\Index(name: 'exercise_x_muscle_updater', columns: ['updater'])]
#[ORM\Entity]
class ExerciseXMuscle
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'muscle_use', type: 'integer', nullable: false, options: ['comment' => 'Beanspruchung des Muskels'])]
    private int $muscleUse;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Exercises')]
    private Exercises $exercise;

    #[ORM\JoinColumn(name: 'muscle', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Muscles')]
    private Muscles $muscle;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;
}
