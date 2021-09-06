<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\DBAL\Types\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * The Address class is the main representation of a geographic address.
 * Each Site has one `main` address and, optionally, an extra `vehicle`
 * address.
 *
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={
 *         "skip_null_values" = false,
 *     }
 * )
 */
class Address
{
    const DEFAULT_LAT = 43.8168891505918;
    const DEFAULT_LNG = 5.045658092187997;

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
     * Address type. List of the allowed Address types:
     * - `main` for the main site address
     * - `vehicle` for the address to use with a vehicle.
     *
     * @ORM\Column(name="type", type="enum_address_type", nullable=false)
     */
    private ?string $type = AddressType::ADDRESS_MAIN;

    /**
     * First line of the address.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address = '';

    /**
     * Second line of the address.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address2 = '';

    /**
     * Zip code.
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $zipcode = '';

    /**
     * City.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city = '';

    /**
     * Country.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country = '';

    /**
     * GPS coordinates - Latitude.
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $lat = self::DEFAULT_LAT;

    /**
     * GPS coordinates - Longitude.
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $lng = self::DEFAULT_LNG;

    /**
     * @ORM\OneToOne(targetEntity=Site::class, mappedBy="mainAddress", cascade={"persist", "remove"})
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity=Site::class, mappedBy="vehicleAddress", cascade={"persist", "remove"})
     */
    private $siteVehicle;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

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

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        // unset the owning side of the relation if necessary
        if (null === $site && null !== $this->site) {
            $this->site->setMainAddress(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $site && $site->getMainAddress() !== $this) {
            $site->setMainAddress($this);
        }

        $this->site = $site;

        return $this;
    }
}
