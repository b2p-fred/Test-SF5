<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 *
 * @ApiResource
 */
class Company
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
    }

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * [Groups(['company:list', 'company:item'])]
     */
    private string $name;

    /**
     * @var Building|null
     *
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="companies")
     */
    private ?Building $building;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
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
