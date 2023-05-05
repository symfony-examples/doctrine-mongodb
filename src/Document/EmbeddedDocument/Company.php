<?php

namespace App\Document\EmbeddedDocument;

use App\Document\AbstractDocument;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\Collection;

#[ODM\Document(collection: 'Company')]
class Company extends AbstractDocument
{
    #[ODM\Field(type: 'string', nullable: false)]
    private string $name;

    #[ODM\Field(type: 'string', nullable: false)]
    #[ODM\UniqueIndex]
    private string $siret;

    #[ODM\EmbedOne(targetDocument: Registration::class)]
    private Registration $registration;

    /** @psalm-var Collection<Address> */
    #[ODM\EmbedMany(targetDocument: Address::class)]
    private Collection $addresses;

    #[ODM\Field(type: 'bool', nullable: false)]
    private bool $active;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function setRegistration(Registration $registration): self
    {
        $this->registration = $registration;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAddresses(): ArrayCollection
    {
        return $this->addresses;
    }

    public function setAddresses(ArrayCollection $addresses): self
    {
        $this->addresses = $addresses;

        return $this;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }
}