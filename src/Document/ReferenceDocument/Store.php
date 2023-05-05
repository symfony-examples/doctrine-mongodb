<?php

namespace App\Document\ReferenceDocument;

use App\Document\AbstractDocument;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'Store')]
class Store extends AbstractDocument
{
    #[ODM\Field(type: 'string', nullable: false)]
    private ?string $name;

    /** @psalm-var Collection<Product> */
    #[ODM\ReferenceMany(nullable: false, targetDocument: Product::class, cascade: ['persist', 'delete'], mappedBy: 'store')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(Product $address): self
    {
        if (!$this->products->contains($address)) {
            $this->products->add($address);
        }

        return $this;
    }

    public function removeProduct(Product $address): self
    {
        if ($this->products->contains($address)) {
            $this->products->removeElement($address);
        }

        return $this;
    }
}
