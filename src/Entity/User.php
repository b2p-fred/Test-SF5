<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\DBAL\Types\HumanGenderType;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The User class is the main representation of an application's user.
 *
 * -----
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 * )
 * @ApiFilter(PropertyFilter::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Using the Symfony Uuid generators to create unique identifiers
     * ($ composer require symfony/uid)
     * See https://symfony.com/doc/current/components/uid.html.
     * See https://github.com/symfony/symfony/issues/41772 to understand why
     * it is not possible to generate a UuidV4 with a generator.
     * Thus, it may be very interesting to use the ramsey/uuid to get UUID v4
     * as strings in the database ? For the moment, uuid will need conversions -)
     * -----.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, name="id")
     *
     * @Groups({"user:read", "user:write"})
     */
    private UuidV4 $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $email;

    /**
     * @param array<string> $roles The user roles
     * @ORM\Column(type="json")
     *
     * @Groups({"user:read"})
     */
    private array $roles = [];

    /**
     * The plainPassword is used to provide a password that will be encoded before
     * persisting in the database (e.g. password in a login form or user creation
     * by an API endpoint).
     *
     * @Assert\NotBlank()
     */
    private ?string $plainPassword;

    /**
     * The hashed password.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $password;

    /**
     * The user's first name.
     *
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $firstName;

    /**
     * The user's last name.
     *
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $lastName;

    /**
     * The user's language.
     *
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=5)
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $language = 'fr-fr';

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;

    /**
     * The user's gender.
     *
     * @ORM\Column(name="gender", type="enum_human_gender", nullable=false)
     *
     * @Groups({"user:read", "user:write"})
     */
    private string $gender = HumanGenderType::GENDER_UNKNOWN;

    /**
     * The user's date of birth.
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Groups({"user:read", "user:write"})
     */
    private $birthdate;

    /**
     * The company the user is working for... I choose to make this an embedded
     * resource. Thus, the company information are available for the user without
     * an extra API request. Else I would have got only the company IRI
     * To make it possible, I added the user:read group for some properties of
     * the company.
     * -----.
     *
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     *
     * @Groups({"user:read", "user:write"})
     */
    private ?Company $company;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * The user's name is composed of the first name and last name.
     */
    public function getName(): ?string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;

        return $this;
    }
}
