<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\DBAL\Types\DocumentType;
use App\DBAL\Types\LanguageType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * The Document class is the main representation of an application's document.
 * A security protocol is composed of several documents: one `main` document and
 * some `annex` documents.
 *
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={
 *         "skip_null_values" = false,
 *     }
 * )
 */
class Document
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
     * The DocumentType class defines the allowed Document types:
     * - `main` for the main document in a security protocole
     * - `annex` for an annex document.
     *
     * @ORM\Column(name="type", type="enum_document_type", nullable=false)
     */
    private string $type = DocumentType::DOCUMENT_MAIN;

    /**
     * Name of the document.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Title ... may be a slug from the name?
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title;

    /**
     * Multi line description of the document.
     *
     * @ORM\Column(type="text", options={"default":""}, nullable=true)
     */
    private ?string $description = '';

    /**
     * Contact preferred language.
     *
     * @ORM\Column(type="string", length=5, options={"default":"fr-FR"})
     */
    private string $language = LanguageType::LANG_DEFAULT;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $filename;

    /**
     * The site the document is attached to.
     *
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="documents")
     */
    private $site;

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

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

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
