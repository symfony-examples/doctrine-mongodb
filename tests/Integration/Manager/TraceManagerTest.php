<?php

namespace App\Tests\Integration\Manager;

use App\Document\SecureDocument\Trace;
use App\Manager\TraceManager;
use App\Tests\Integration\AbstractKernelTestCase;

class TraceManagerTest extends AbstractKernelTestCase
{
    private TraceManager $traceManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->traceManager = new TraceManager($this->documentManager);
    }

    public function testFindAll()
    {
        $this->loadFixtures();

        $traces = $this->traceManager->findAll();
        self::assertCount(1, $traces);
        self::assertIsIterable($traces);

        foreach ($traces as $trace) {
            self::assertInstanceOf(Trace::class, $trace);
        }
    }
}
