<?php

namespace App\Tests\Integration\Manager;

use App\Document\ReferenceDocument\Product;
use App\Document\ReferenceDocument\Store;
use App\Manager\ProductManager;
use App\Tests\Integration\AbstractKernelTestCase;

class ProductManagerTest extends AbstractKernelTestCase
{
    private ProductManager $productManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->productManager = new ProductManager($this->documentManager);
    }

    public function testFindAll()
    {
        $this->loadFixtures();

        $products = $this->productManager->findAll();
        self::assertCount(1, $products);
        self::assertIsIterable($products);

        foreach ($products as $product) {
            self::assertInstanceOf(Product::class, $product);
            self::assertInstanceOf(Store::class, $product->getStore());
        }
    }
}
