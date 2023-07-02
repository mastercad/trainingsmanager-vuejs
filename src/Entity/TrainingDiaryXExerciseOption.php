<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXExerciseOption
 */
#[ORM\Table(name: 'training_diary_x_exercise_option')]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_exercise_option_fk', columns: ['exercise_option'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_t_d_x_e_fk', columns: ['training_plan_exercise'])]
#[ORM\Index(name: 'index_training_diary_x_exercise_option_update_user_fk', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_training_diary_x_exercise_option_id', columns: ['id'])]
#[ORM\Entity]
class TrainingDiaryXExerciseOption
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'option_value', type: 'string', length: 255, nullable: false)]
    private string $optionValue;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'exercise_option', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'ExerciseOptions')]
    private ExerciseOptions $exerciseOption;

    #[ORM\JoinColumn(name: 'training_plan_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private TrainingPlanXExercise $trainingPlanExercise;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;
}
