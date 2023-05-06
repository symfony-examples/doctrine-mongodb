<?php

namespace App\Document\ReferenceDocument;

use App\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'Product')]
class Product extends AbstractDocument
{
    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $name;

    #[ODM\Field(type: 'float', nullable: false)]
    private ?float $amount;

    #[ODM\ReferenceOne(nullable: false, targetDocument: Store::class, cascade: 'persist', inversedBy: 'products')]
    private Store $store;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function setStore(Store $store): self
    {
        $this->store = $store;

        return $this;
    }
}
