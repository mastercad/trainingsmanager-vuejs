<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXTrainingPlanExercise
 */
#[ORM\Table(name: 'training_diary_x_training_plan_exercise')]
#[ORM\Index(name: 'index_training_diary_x_training_plan_exercise_training_diary_fk', columns: ['training_diary'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_id', columns: ['id'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_t_p_x_e_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_update_user_fk', columns: ['updater'])]
#[ORM\Entity]
class TrainingDiaryXTrainingPlanExercise
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'comment', type: 'string', length: 255, nullable: false)]
    private string $comment;

    #[ORM\Column(name: 'flag_finished', type: 'boolean', nullable: false)]
    private bool $flagFinished = false;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'training_diary', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingDiaries')]
    private TrainingDiaries $trainingDiary;

    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'TrainingPlanXExercise')]
    private TrainingPlanXExercise $trainingPlanXExercise;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;
}
