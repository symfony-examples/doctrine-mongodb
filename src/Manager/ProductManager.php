<?php

namespace App\Manager;

use App\Document\ReferenceDocument\Product;
use Doctrine\ODM\MongoDB\DocumentManager;

class ProductManager
{
    public function __construct(protected DocumentManager $documentManager)
    {
    }

    /** @psalm-return Product[] */
    public function findAll(): array
    {
        return $this->documentManager->getRepository(Product::class)->findAll();
    }
}
