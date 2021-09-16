<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\DBAL\Types\RelationType;
use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=RelationRepository::class)
 *
 * @ApiResource()
 */
class Relation
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, name="id")
     */
    private UuidV4 $id;

    /**
     * The RelationType class defines the allowed Relation types:
     * - 'created' for a freshly created relation
     * - 'sent' for a document which just got sent
     * - 'approved' for a document approved by the B-party.
     * - 'refused' for a document refused by the B-party.
     *
     * @ORM\Column(name="type", type="enum_relation_type", nullable=false)
     */
    private string $type = RelationType::RELATION_CREATED;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $sender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $recipient;

    /**
     * @ORM\ManyToOne(targetEntity=DocumentVersion::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private DocumentVersion $protocol;

    /**
     * Multi line comment for the last status.
     *
     * @ORM\Column(type="text", options={"default":""}, nullable=true)
     */
    private ?string $comments = '';

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): ?UuidV4
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

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getProtocol(): ?DocumentVersion
    {
        return $this->protocol;
    }

    public function setProtocol(?DocumentVersion $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
