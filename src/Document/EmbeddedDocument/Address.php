<?php

namespace App\Document\EmbeddedDocument;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\EmbeddedDocument]
class Address
{
    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $streetNumber = null;

    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $streetName = null;

    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $city = null;

    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $zipCode = null;

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;

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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
