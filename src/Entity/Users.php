<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(formats: ['json'])]
/**
 * Users
 */
#[ORM\Table(name: 'users')]
#[ORM\Index(name: 'IDX_users_id', columns: ['id'])]
#[ORM\Index(name: 'IDX_users_state', columns: ['state'])]
#[ORM\Index(name: 'IDX_users_creator', columns: ['creator'])]
#[ORM\Index(name: 'IDX_users_updater', columns: ['updater'])]
#[ORM\UniqueConstraint(name: 'UN_users_login', columns: ['login'])]
#[ORM\UniqueConstraint(name: 'UN_users_email', columns: ['email'])]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     *
     * @var UuidInterface
     */
    #[ORM\Column(name: 'id', type: 'uuid', unique: true, nullable: false, options: ['unsigned' => true])]
    #[ORM\Id]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'first_name', type: 'string', length: 250, nullable: false)]
    private $firstName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'last_name', type: 'string', length: 250, nullable: false)]
    private $lastName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'email', type: 'string', length: 250, nullable: false)]
    #[Assert\NotBlank]
    private $email;

    /**
     * @var string
     */
    #[ORM\Column(name: 'login', type: 'string', length: 250, nullable: false)]
    private $login;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'profile_picture_path', type: 'string', length: 255, nullable: true)]
    private $profilePicturePath;

    /**
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 4096)]
    private $plainPassword;

    /**
     * @var string
     */
    #[ORM\Column(name: 'password', type: 'string', length: 250, nullable: false)]
    private $password;

    /**
     * @var string[]
     */
    #[ORM\Column(name: 'roles', type: 'json', nullable: false)]
    private $roles;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'facebook_id', type: 'string', length: 250, nullable: true)]
    private $facebookId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'twitter_id', type: 'string', length: 250, nullable: true)]
    private $twitterId;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'google_plus_id', type: 'string', length: 250, nullable: true)]
    private $googlePlusId;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'session_timeout', type: 'integer', nullable: true)]
    private $sessionTimeout;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'last_login', type: 'datetime', nullable: true)]
    private $lastLogin;

    /**
     * @var int|null
     */
    #[ORM\Column(name: 'login_count', type: 'integer', nullable: true)]
    private $loginCount;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'session_id', type: 'string', length: 250, nullable: true)]
    private $sessionId;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'flag_logged_in', type: 'boolean', nullable: false)]
    private $flagLoggedIn = '0';

    /**
     * @var bool|null
     */
    #[ORM\Column(name: 'flag_multilogin', type: 'boolean', nullable: true)]
    private $flagMultilogin;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'validate_hash', type: 'string', length: 250, nullable: true)]
    private $validateHash;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created', type: 'datetime', nullable: false)]
    private $created;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(name: 'updated', type: 'datetime', nullable: true)]
    private $updated;

    /**
     * @var Users
     */
    #[ORM\ManyToOne(targetEntity: 'Users')]
    #[ORM\JoinColumn(name: 'creator', referencedColumnName: 'id')]
    private $creator;

    /**
     * @var UserState
     */
    #[ORM\JoinColumn(name: 'state', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'UserState')]
    private $state;

    /**
     * @var Users
     */
    #[ORM\JoinColumn(name: 'updater', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: 'Users')]
    private $updater;

    public function __construct()
    {
        $this->roles = [];
    }

    /**
     * @throws Exception
     */
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
     *
     * @return self
     */
    public function setId(UuidInterface $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName.
     *
     * @return self
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName.
     *
     * @return self
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of login.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set the value of login.
     *
     * @return self
     */
    public function setLogin(string $login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get the value of profilePicturePath.
     *
     * @return string|null
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Set the value of profilePicturePath.
     *
     * @param string|null $profilePicturePath
     *
     * @return self
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    public function getPlainPassword(): ?string
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
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @return self
     */
    public function setPassword(string $password)
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
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of facebookId.
     *
     * @return string|null
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set the value of facebookId.
     *
     * @param string|null $facebookId
     *
     * @return self
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get the value of twitterId.
     *
     * @return string|null
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set the value of twitterId.
     *
     * @param string|null $twitterId
     *
     * @return self
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get the value of googlePlusId.
     *
     * @return string|null
     */
    public function getGooglePlusId()
    {
        return $this->googlePlusId;
    }

    /**
     * Set the value of googlePlusId.
     *
     * @param string|null $googlePlusId
     *
     * @return self
     */
    public function setGooglePlusId($googlePlusId)
    {
        $this->googlePlusId = $googlePlusId;

        return $this;
    }

    /**
     * Get the value of sessionTimeout.
     *
     * @return int|null
     */
    public function getSessionTimeout()
    {
        return $this->sessionTimeout;
    }

    /**
     * Set the value of sessionTimeout.
     *
     * @param int|null $sessionTimeout
     *
     * @return self
     */
    public function setSessionTimeout($sessionTimeout)
    {
        $this->sessionTimeout = $sessionTimeout;

        return $this;
    }

    /**
     * Get the value of lastLogin.
     *
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set the value of lastLogin.
     *
     * @param \DateTime|null $lastLogin
     *
     * @return self
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get the value of loginCount.
     *
     * @return int|null
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set the value of loginCount.
     *
     * @param int|null $loginCount
     *
     * @return self
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get the value of sessionId.
     *
     * @return string|null
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the value of sessionId.
     *
     * @param string|null $sessionId
     *
     * @return self
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get the value of flagLoggedIn.
     *
     * @return bool
     */
    public function getFlagLoggedIn()
    {
        return $this->flagLoggedIn;
    }

    /**
     * Set the value of flagLoggedIn.
     *
     * @return self
     */
    public function setFlagLoggedIn(bool $flagLoggedIn)
    {
        $this->flagLoggedIn = $flagLoggedIn;

        return $this;
    }

    /**
     * Get the value of flagMultilogin.
     *
     * @return bool|null
     */
    public function getFlagMultilogin()
    {
        return $this->flagMultilogin;
    }

    /**
     * Set the value of flagMultilogin.
     *
     * @param bool|null $flagMultilogin
     *
     * @return self
     */
    public function setFlagMultilogin($flagMultilogin)
    {
        $this->flagMultilogin = $flagMultilogin;

        return $this;
    }

    /**
     * Get the value of validateHash.
     *
     * @return string|null
     */
    public function getValidateHash()
    {
        return $this->validateHash;
    }

    /**
     * Set the value of validateHash.
     *
     * @param string|null $validateHash
     *
     * @return self
     */
    public function setValidateHash($validateHash)
    {
        $this->validateHash = $validateHash;

        return $this;
    }

    /**
     * Get the value of created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created.
     *
     * @param \DateTime $created
     *
     * @return self
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of updated.
     *
     * @return \DateTime|null
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of updated.
     *
     * @param \DateTime|null $updated
     *
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of creator.
     *
     * @return Users
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set the value of creator.
     *
     * @return self
     */
    public function setCreator(Users $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get the value of state.
     *
     * @return UserState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state.
     *
     * @return self
     */
    public function setState(UserState $state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of updater.
     *
     * @return Users
     */
    public function getUpdater()
    {
        return $this->updater;
    }

    /**
     * Set the value of updater.
     *
     * @return self
     */
    public function setUpdater(Users $updater)
    {
        $this->updater = $updater;

        return $this;
    }

    /*******************************************************************************
     *
     *  UserInterface functions
     *
     *******************************************************************************/
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    public function getUsername()
    {
        return $this->firstName.' '.$this->lastName;
    }
}