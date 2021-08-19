<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=BuildingRepository::class)
 *
 * @ApiResource
 */
class Building
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, name="id")
     *
     * @var UuidV4
     */
    private UuidV4 $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->companies = new ArrayCollection();
    }

    /**
     * @param string $name a name property - this description will be available in the API documentation too
     *
     * @ORM\Column(type="string", length=255)
     *
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="string",
     *             "enum"={"one", "two"},
     *             "example"="one"
     *         }
     *     }
     * )     */
    private string $name;

    /**
     * @param string $address a name property - this description will be available in the API documentation too
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $address;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $zipcode;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    /**
     * @var Collection&iterable<Company>
     *
     * @ORM\OneToMany(targetEntity=Company::class, mappedBy="building")
     */
    private $companies;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private float $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private float $lng;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setBuilding($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getBuilding() === $this) {
                $company->setBuilding(null);
            }
        }

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
