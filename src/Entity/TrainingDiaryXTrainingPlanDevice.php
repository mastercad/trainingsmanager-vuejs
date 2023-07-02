<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXTrainingPlanDevice
 */
#[ORM\Table(name: 'training_diary_x_training_plan_device')]
#[ORM\Index(name: 'index_training_diary_x_training_plan_exercise_training_diary_fk', columns: ['training_diary'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_id', columns: ['id'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_t_p_x_e_fk', columns: ['training_plan_x_device'])]
#[ORM\Index(name: 'training_diary_x_training_plan_exercise_update_user_fk', columns: ['updater'])]
#[ORM\Entity]
class TrainingDiaryXTrainingPlanDevice
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'training_plan_x_device', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $trainingPlanXDevice;

    #[ORM\Column(name: 'training_diary', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $trainingDiary;

    #[ORM\Column(name: 'comment', type: 'string', length: 255, nullable: false)]
    private string $comment;

    #[ORM\Column(name: 'flag_finished', type: 'boolean', nullable: false)]
    private bool $flagFinished = '0';

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    private DateTime $created;

    #[ORM\Column(name: 'creator', type: 'integer', nullable: false, options: ['unsigned' => true])]
    private int $creator;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\Column(name: 'updater', type: 'integer', nullable: true, options: ['unsigned' => true])]
    private int|null $updater = null;
}
