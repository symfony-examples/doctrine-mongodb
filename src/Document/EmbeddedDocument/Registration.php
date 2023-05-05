<?php

namespace App\Document\EmbeddedDocument;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Registration
{
    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $city = null;

    #[ODM\Field(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date = null;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
