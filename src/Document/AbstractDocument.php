<?php

namespace App\Document;

use DateTime;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\MappedSuperclass]
#[ODM\HasLifecycleCallbacks]
abstract class AbstractDocument
{
    #[ODM\Id(type: 'string', strategy: 'auto')]
    protected ?string $id = null;

    #[ODM\Field(type: 'date', nullable: false)]
    protected ?DateTimeInterface $createdDate = null;

    #[ODM\Field(type: 'date', nullable: false)]
    protected ?DateTimeInterface $lastUpdateDate = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedDate(): ?DateTimeInterface
    {
        return $this->createdDate;
    }

    public function getLastUpdateDate(): ?DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    #[ODM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdDate = new DateTime();
        $this->lastUpdateDate = new DateTime();
    }

    #[ODM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->lastUpdateDate = new DateTime();
    }
}
