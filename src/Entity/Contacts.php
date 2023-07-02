<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/*#[ApiResource(formats: ['xml', 'json', 'ld+json', 'csv' => ['text/csv']])]*/
/**
 * Contact
 */
#[ApiResource(formats: ['json', 'jsonld'])]
#[Orm\Table('contact')]
#[Orm\Entity()]
class Contacts
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'first_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private string $lastName;

    #[ORM\Column(name: 'email_address', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank()]
    private string $emailAddress;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): string|null
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string|null
    {
        return $this->lastName;
    }

    public function setLastName(string|null $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmailAddress(): string|null
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string|null $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }
}
