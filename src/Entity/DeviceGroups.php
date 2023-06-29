<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DeviceGroups
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
#[ORM\Table(name: 'device_groups')]
#[ORM\Index(name: 'device_group_creator', columns: ['creator'])]
#[ORM\Index(name: 'device_group_id', columns: ['id'])]
#[ORM\Index(name: 'device_group_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'device_group_name', columns: ['name'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class DeviceGroups
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['read', 'write'])]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'seo_link', type: 'string', length: 250, nullable: false)]
    #[Groups(['read', 'write'])]
    private $seoLink;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $creator;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['read'])]
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[Groups(['read'])]
    private $updater;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    #[Groups(['read'])]
    private $updated;

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of seoLink
     *
     * @return string
     */
    public function getSeoLink()
    {
        return $this->seoLink;
    }

    /**
     * Set the value of seoLink
     *
     * @param string $seoLink
     *
     * @return self
     */
    public function setSeoLink(string $seoLink)
    {
        $this->seoLink = $seoLink;

        return $this;
    }

    /**
     * Get the value of creator
     *
     * @return Users
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @param Users $creator
     *
     * @return self
     */
    public function setCreator(Users $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created
     *
     * @param \DateTime $created
     *
     * @return self
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updater
     *
     * @return Users
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set the value of updater
     *
     * @param Users $updater
     *
     * @return self
     */ 
    public function setUpdater(Users $updater)
    {
        $this->updater = $updater;

        return $this;
    }

    /**
     * Get the value of updated
     *
     * @return  \DateTime|null
     */ 
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @param  \DateTime|null  $updated
     *
     * @return  self
     */ 
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
