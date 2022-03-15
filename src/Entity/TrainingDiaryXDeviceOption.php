<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingDiaryXDeviceOption
 *
 * @ORM\Table(name="training_diary_x_device_option", uniqueConstraints={@ORM\UniqueConstraint(name="unique_training_diary_x_device_option_id", columns={"id"})}, indexes={@ORM\Index(name="index_training_diary_x_device_option_create_user_fk", columns={"creator"}), @ORM\Index(name="index_training_diary_x_device_option_device_option_fk", columns={"device_option"}), @ORM\Index(name="index_training_diary_x_device_option_t_p_e_fk", columns={"training_plan_exercise"}), @ORM\Index(name="index_training_diary_x_device_option_update_user_fk", columns={"updater"})})
 * @ORM\Entity
 */
class TrainingDiaryXDeviceOption
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
     * @var \DeviceOptions
     *
     * @ORM\ManyToOne(targetEntity="DeviceOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_option", referencedColumnName="id")
     * })
     */
    private $deviceOption;

    /**
     * @var \TrainingPlanXExercise
     *
     * @ORM\ManyToOne(targetEntity="TrainingPlanXExercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_plan_exercise", referencedColumnName="id")
     * })
     */
    private $trainingPlanExercise;

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
