<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/*#[ApiResource(formats: ['xml', 'json', 'ld+json', 'csv' => ['text/csv']])]*/
#[ApiResource(formats: ['json', 'jsonld'])]
/**
 * Contact
 */
#[Orm\Table("contact")]
#[Orm\Entity()]
class Contacts
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'first_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private $firstName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'last_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private $lastName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'email_address', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private $emailAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }
}
