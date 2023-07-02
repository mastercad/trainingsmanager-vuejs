<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MuscleGroups
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete()
    ]
)]
#[ORM\Table(name: 'muscle_groups')]
#[ORM\Index(name: 'muscle_group_creator', columns: ['creator'])]
#[ORM\Index(name: 'muscle_group_id', columns: ['id'])]
#[ORM\Index(name: 'muscle_group_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'unique_muscle_group_name', columns: ['name'])]
#[ORM\UniqueConstraint(name: 'unique_muscle_group_seo_link', columns: ['seo_link'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class MuscleGroups
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $name;

    #[ORM\Column(name: 'seo_link', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $seoLink;

    #[ORM\Column(name: 'color', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private string $color;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $creator;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private Users $updater;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private DateTime|null $updated = null;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of seoLink
     */
    public function getSeoLink(): string
    {
        return $this->seoLink;
    }

    /**
     * Set the value of seoLink
     */
    public function setSeoLink(string $seoLink): self
    {
        $this->seoLink = $seoLink;

        return $this;
    }

    /**
     * Get the value of color
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the value of created
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set the value of created
     */
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated
     */
    public function getUpdated(): DateTime|null
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     */
    public function setUpdated(DateTime|null $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator
     */
    public function getCreator(): Users
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     */
    public function setCreator(Users $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of updater
     */
    public function getUpdater(): Users
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     */
    public function setUpdater(Users $updater): self
    {
        $this->updater = $updater;

        return $this;
    }
}
