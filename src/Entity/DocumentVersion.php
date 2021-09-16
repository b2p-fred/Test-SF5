<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 *
 * @ApiResource()
 */
class DocumentVersion
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
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Document $document;

    /**
     * @ORM\Column(type="integer")
     */
    private int $version = 0;

    /**
     * Multi line description of the version (e.g. release notes).
     *
     * @ORM\Column(type="text", options={"default":""}, nullable=true)
     */
    private ?string $description = '';

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $initiator;

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

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

    public function getInitiator(): ?User
    {
        return $this->initiator;
    }

    public function setInitiator(?User $user): self
    {
        $this->initiator = $user;

        return $this;
    }
}
