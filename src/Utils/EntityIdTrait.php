<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * See https://medium.com/@galopintitouan/auto-increment-is-the-devil-using-uuids-in-symfony-and-doctrine-71763721b9a9
 * --- Very interesting explanation why having id and uuid is the winning combo!
 */
trait EntityIdTrait
{
    /**
     * @var int The unique auto incremented primary key.
     *          -----
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected ?int $id;

    /**
     * @var UuidInterface The internal primary identity key.
     *                    ------
     *                    Using the Symfony Uuid generators to create unique identifiers
     *                    ($ composer require symfony/uid)
     *                    See https://symfony.com/doc/current/components/uid.html.
     *                    See https://github.com/symfony/symfony/issues/41772 to understand why
     *                    it is not possible to generate a UuidV4 with a generator.
     *                    Thus it is still very interesting to use the ramsey/uuid to get UUID v4
     *                    as strings in the database.
     *                    -----.
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator)
     */
    protected UuidInterface $uuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }
}
