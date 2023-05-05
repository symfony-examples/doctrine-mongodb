<?php

namespace App\DataFixture;

use App\Document\ReferenceDocument\Product;
use App\Document\ReferenceDocument\Store;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductDataFixture implements ODMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(
            (new Product())
                ->setName('StoreName')
                ->setAmount(199.99)
                ->setStore(
                    (new Store())
                        ->setName('StoreName')
                )
        );

        $manager->flush();
    }
}
