<?php

namespace App\Manager;

use App\Document\EmbeddedDocument\Company;
use Doctrine\ODM\MongoDB\DocumentManager;

class CompanyManager
{
    public function __construct(protected DocumentManager $documentManager)
    {
    }

    /** @psalm-return Company[] */
    public function findAll(): array
    {
        return $this->documentManager->getRepository(Company::class)->findAll();
    }
}
