<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\DBAL\Types\AddressType;
use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * The Site class is the main representation of an application's site.
 * It is used for representing the sites which are related to the security protocols.
 *
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={
 *         "skip_null_values" = false,
 *     }
 * )
 */
class Site
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
        $this->contacts = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    /**
     * Name of the site.
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $name = '';

    /**
     * Title ... may be a slug from the name?
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title = '';

    /**
     * Multi line description of the site.
     *
     * @ORM\Column(type="text", options={"default":""}, nullable=true)
     */
    private ?string $description = '';

    /**
     * The list of all the contacts attached to the site.
     * ---
     * This resource is a Site sub-resource, whereas it is possible to
     * GET /api/sites/{uuid}/contacts to get the list of the contacts
     * related to a Site.
     *
     * @var Collection&iterable<Contact>
     *
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="site")
     *
     * @ApiSubresource()
     */
    private $contacts;

    /**
     * The list of the Documents attached to the site.
     *
     * @var Collection&iterable<Document>
     *
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="site")
     */
    private $documents;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="site", cascade={"persist", "remove"})
     */
    private ?Address $mainAddress = null;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, inversedBy="siteVehicle", cascade={"persist", "remove"})
     */
    private ?Address $vehicleAddress = null;

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setSite($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getSite() === $this) {
                $contact->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setSite($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getSite() === $this) {
                $document->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Relation[]
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): self
    {
        if (!$this->relations->contains($relation)) {
            $this->relations[] = $relation;
            $relation->setSite($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getSite() === $this) {
                $relation->setSite(null);
            }
        }

        return $this;
    }

    public function getMainAddress(): ?Address
    {
        return $this->mainAddress;
    }

    public function setMainAddress(?Address $address): self
    {
        $address->setType(AddressType::ADDRESS_MAIN);
        $this->mainAddress = $address;

        return $this;
    }

    public function getVehicleAddress(): ?Address
    {
        return $this->vehicleAddress;
    }

    public function setVehicleAddress(?Address $address): self
    {
        $address->setType(AddressType::ADDRESS_VEHICLE);
        $this->vehicleAddress = $address;

        return $this;
    }
}
