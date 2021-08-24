<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"company:read", "company:item:get"}},
 *     denormalizationContext={"groups"={"company:write"}},
 * )
 */
class Company
{
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
     * The name of the company.
     * Note that it is an embedded resource for the user.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"company:read", "company:write", "user:read"})
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="companies")
     *
     * @Groups({"company:read", "company:write"})
     */
    private ?Building $building;

    /**
     * This resource is a Company sub-resource, whereas it is possible to
     * GET /api/companies/{uuid}/users to get the list of the users related to
     * a company.
     *
     * @var Collection&iterable<User>
     *
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
     *
     * @ApiSubresource()
     * @Groups({"company:read", "company:write"})
     */
    private $users;

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

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }
}
