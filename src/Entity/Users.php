<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Users
 */
#[ApiResource(formats: ['json'])]
#[ORM\Table(name: 'users')]
#[ORM\Index(name: 'IDX_users_id', columns: ['id'])]
#[ORM\Index(name: 'IDX_users_state', columns: ['state'])]
#[ORM\Index(name: 'IDX_users_creator', columns: ['creator'])]
#[ORM\Index(name: 'IDX_users_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'UN_users_login', columns: ['login'])]
#[ORM\UniqueConstraint(name: 'UN_users_email', columns: ['email'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields:['email'], message:'This value is already used.')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: 'id', type: 'uuid', unique: true, nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    private UuidInterface $id;

    #[ORM\Column(name: 'first_name', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank]
    private string $lastName;

    #[ORM\Column(name: 'email', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank]
    private string $email;

    #[ORM\Column(name: 'login', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank]
    private string $login;

    #[ORM\Column(name: 'profile_picture_path', type: 'string', length: 255, nullable: true)]
    private string|null $profilePicturePath = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 4096)]
    private string|null $plainPassword = null;

    #[ORM\Column(name: 'password', type: 'string', length: 250, nullable: false)]
    private string|null $password;

    /** @var string[] */
    #[ORM\Column(name: 'roles', type: 'json', nullable: false)]
    private array $roles;

    #[ORM\Column(name: 'facebook_id', type: 'string', length: 250, nullable: true)]
    private string|null $facebookId = null;

    #[ORM\Column(name: 'twitter_id', type: 'string', length: 250, nullable: true)]
    private string|null $twitterId = null;

    #[ORM\Column(name: 'google_plus_id', type: 'string', length: 250, nullable: true)]
    private string|null $googlePlusId = null;

    #[ORM\Column(name: 'session_timeout', type: 'integer', nullable: true)]
    private int|null $sessionTimeout = null;

    #[ORM\Column(name: 'last_login', type: 'datetime', nullable: true)]
    private DateTime|null $lastLogin = null;

    #[ORM\Column(name: 'login_count', type: 'integer', nullable: true)]
    private int|null $loginCount = null;

    #[ORM\Column(name: 'session_id', type: 'string', length: 250, nullable: true)]
    private string|null $sessionId = null;

    #[ORM\Column(name: 'flag_logged_in', type: 'boolean', nullable: false)]
    private bool $flagLoggedIn = false;

    #[ORM\Column(name: 'flag_multilogin', type: 'boolean', nullable: true)]
    private bool|null $flagMultilogin = null;

    #[ORM\Column(name: 'validate_hash', type: 'string', length: 250, nullable: true)]
    private string|null $validateHash = null;

    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    private DateTime $created;

    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private DateTime|null $updated = null;

    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    private Users $creator;

    #[ORM\JoinColumn(name: 'state', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'UserState')]
    private UserState $state;

    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private Users $updater;

    public function __construct()
    {
        $this->roles = [];
    }

    /** @throws Exception */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->id = Uuid::uuid4();
        $this->created = new DateTime('NOW');
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime('NOW');
    }

    /**
     * Get the value of id.
     *
     * @return : UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     */
    public function setId(UuidInterface $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of firstName.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName.
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName.
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of login.
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set the value of login.
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of profilePicturePath.
     */
    public function getProfilePicturePath(): string|null
    {
        return $this->profilePicturePath;
    }

    /**
     * Set the value of profilePicturePath.
     */
    public function setProfilePicturePath(string|null $profilePicturePath): self
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    public function getPlainPassword(): string|null
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;

        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    /**
     * Get the value of password.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roles.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Set the value of roles.
     *
     * @var string[] $roles
     */
    public function setRoles(mixed $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of facebookId.
     */
    public function getFacebookId(): string|null
    {
        return $this->facebookId;
    }

    /**
     * Set the value of facebookId.
     */
    public function setFacebookId(string|null $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get the value of twitterId.
     */
    public function getTwitterId(): string|null
    {
        return $this->twitterId;
    }

    /**
     * Set the value of twitterId.
     */
    public function setTwitterId(string|null $twitterId): self
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get the value of googlePlusId.
     */
    public function getGooglePlusId(): string|null
    {
        return $this->googlePlusId;
    }

    /**
     * Set the value of googlePlusId.
     */
    public function setGooglePlusId(string|null $googlePlusId): self
    {
        $this->googlePlusId = $googlePlusId;

        return $this;
    }

    /**
     * Get the value of sessionTimeout.
     */
    public function getSessionTimeout(): int|null
    {
        return $this->sessionTimeout;
    }

    /**
     * Set the value of sessionTimeout.
     */
    public function setSessionTimeout(int|null $sessionTimeout): self
    {
        $this->sessionTimeout = $sessionTimeout;

        return $this;
    }

    /**
     * Get the value of lastLogin.
     */
    public function getLastLogin(): DateTime|null
    {
        return $this->lastLogin;
    }

    /**
     * Set the value of lastLogin.
     */
    public function setLastLogin(DateTime|null $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get the value of loginCount.
     */
    public function getLoginCount(): int|null
    {
        return $this->loginCount;
    }

    /**
     * Set the value of loginCount.
     */
    public function setLoginCount(int|null $loginCount): self
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get the value of sessionId.
     */
    public function getSessionId(): string|null
    {
        return $this->sessionId;
    }

    /**
     * Set the value of sessionId.
     */
    public function setSessionId(string|null $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get the value of flagLoggedIn.
     */
    public function getFlagLoggedIn(): bool
    {
        return $this->flagLoggedIn;
    }

    /**
     * Set the value of flagLoggedIn.
     */
    public function setFlagLoggedIn(bool $flagLoggedIn): self
    {
        $this->flagLoggedIn = $flagLoggedIn;

        return $this;
    }

    /**
     * Get the value of flagMultilogin.
     */
    public function getFlagMultilogin(): bool|null
    {
        return $this->flagMultilogin;
    }

    /**
     * Set the value of flagMultilogin.
     */
    public function setFlagMultilogin(bool|null $flagMultilogin): self
    {
        $this->flagMultilogin = $flagMultilogin;

        return $this;
    }

    /**
     * Get the value of validateHash.
     */
    public function getValidateHash(): string|null
    {
        return $this->validateHash;
    }

    /**
     * Set the value of validateHash.
     */
    public function setValidateHash(string|null $validateHash): self
    {
        $this->validateHash = $validateHash;

        return $this;
    }

    /**
     * Get the value of created.
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set the value of created.
     */
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated.
     */
    public function getUpdated(): DateTime|null
    {
        return $this->updated;
    }

    /**
     * Set the value of updated.
     */
    public function setUpdated(DateTime|null $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator.
     */
    public function getCreator(): Users
    {
        return $this->creator;
    }

    /**
     * Set the value of creator.
     */
    public function setCreator(Users $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of state.
     */
    public function getState(): UserState
    {
        return $this->state;
    }

    /**
     * Set the value of state.
     */
    public function setState(UserState $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of updater.
     */
    public function getUpdater(): Users
    {
        return $this->updater;
    }

    /**
     * Set the value of updater.
     */
    public function setUpdater(Users $updater): self
    {
        $this->updater = $updater;

        return $this;
    }

    /**
     * UserInterface functions
     *******************************************************************************/
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = '';
    }

    public function getUsername(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
