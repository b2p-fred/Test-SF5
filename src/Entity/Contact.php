<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\DBAL\Types\ContactType;
use App\DBAL\Types\LanguageType;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * The Contact class is the main representation of an application's person.
 * It is used for representing the sites' persons (responsible, emergency contact, ...)
 * and the mobile access persons.
 *
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={
 *         "skip_null_values" = false,
 *     }
 * )
 */
class Contact
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, name="id")
     */
    private UuidV4 $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    /**
     * Contact type. List of the allowed Contact types:
     * - `simple` for a normal site's contact
     * - `responsible` for the site's responsible
     * - `emergency` for an emergency contact of a site
     * - `visitor` for a visitor of a site (mobile access).
     *
     * @ORM\Column(name="type", type="enum_contact_type", nullable=false)
     */
    private string $type = ContactType::CONTACT;

    /**
     * Contact first name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName = '';

    /**
     * Contact last name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName = '';

    /**
     * Contact mail address.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email = null;

    /**
     * Contact identifier (used only for the mobile access).
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $identifier = null;

    /**
     * Contact password (used only for the mobile access).
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password = null;

    /**
     * Contact preferred language.
     * Consider using the language code (e.g. fr) and a specific local variant (e.g. nl-BE)
     * If only 2 digits are used, it is assumed there is no local variant (fr is the same as fr-fr).
     *
     * @ORM\Column(type="string", length=5, options={"default":"fr-FR"})
     */
    private string $language = LanguageType::LANG_DEFAULT;

    /**
     * Contact international phone number
     * As of E.164:
     * - `+` sign indicating an international number+
     * - 3 digits for the country code (e.g. France is 33, Morocco is 212, ...)
     * - 12 digits for the local phone number.
     *
     * todo: create a validation constraint for this
     *
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private ?string $phone = null;

    /**
     * The site the contact is attached to.
     * -----.
     *
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="contacts")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", nullable=true)
     */
    private ?Site $site;

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
}
