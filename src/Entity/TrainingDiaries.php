<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaries
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Put(),
        new Delete()
    ]
)]
#[ORM\Table(name: 'training_diaries')]
#[ORM\Index(name: 'index_training_diary_create_user_fk', columns: ['creator'])]
#[ORM\Index(name: 'index_training_diary_training_plan_x_exercise_fk', columns: ['training_plan_x_exercise'])]
#[ORM\Index(name: 'index_training_diary_update_user_fk', columns: ['updater'])]
#[ORM\Index(name: 'training_diary_id', columns: ['id'])]
#[ORM\Entity]
class TrainingDiaries
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'comment', type: 'text', length: 65535, nullable: true)]
    private string|null $comment = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'training_plan_x_exercise', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Exercises')]
    private Exercises $trainingPlanXExercise;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;
}
