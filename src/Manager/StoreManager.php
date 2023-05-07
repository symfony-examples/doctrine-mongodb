<?php

namespace App\Manager;

use App\Document\ReferenceDocument\Store;
use Doctrine\ODM\MongoDB\DocumentManager;

class StoreManager
{
    public function __construct(protected DocumentManager $documentManager)
    {
    }

    /** @psalm-return Store[] */
    public function findAll(): array
    {
        return $this->documentManager->getRepository(Store::class)->findAll();
    }
}
