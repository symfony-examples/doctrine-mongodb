<?php

namespace App\Tests\Integration\Manager;

use App\Document\ReferenceDocument\Store;
use App\Manager\StoreManager;
use App\Tests\Integration\AbstractKernelTestCase;

class StoreManagerTest extends AbstractKernelTestCase
{
    private StoreManager $storeManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->storeManager = new StoreManager($this->documentManager);
    }

    public function testFindAll()
    {
        $this->loadFixtures();

        $stores = $this->storeManager->findAll();
        self::assertCount(1, $stores);
        self::assertIsIterable($stores);

        foreach ($stores as $store) {
            self::assertInstanceOf(Store::class, $store);
        }
    }
}
