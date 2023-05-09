<?php

namespace App\DataFixture;

use App\Document\ReferenceDocument\Store;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StoreDataFixture extends Fixture implements ODMFixtureInterface
{
    public const STORE_REFERENCE = 'store';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            $store = (new Store())
                ->setName('StoreName')
        );

        $manager->flush();

        $this->addReference(self::STORE_REFERENCE, $store);
    }
}
