<?php

namespace App\Manager;

use App\Document\SecureDocument\Trace;
use Doctrine\ODM\MongoDB\DocumentManager;

class TraceManager
{
    public function __construct(protected DocumentManager $documentManager)
    {
    }

    /** @psalm-return Trace[] */
    public function findAll(): array
    {
        return $this->documentManager->getRepository(Trace::class)->findAll();
    }
}
