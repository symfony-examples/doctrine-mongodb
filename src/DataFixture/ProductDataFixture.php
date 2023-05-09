<?php

namespace App\DataFixture;

use App\Document\ReferenceDocument\Product;
use App\Document\ReferenceDocument\Store;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductDataFixture extends Fixture implements ODMFixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Store $store */
        $store = $this->getReference(StoreDataFixture::STORE_REFERENCE);

        $manager->persist(
            (new Product())
                ->setName('First')
                ->setAmount(199.99)
                ->setStore($store)
        );

        $manager->persist(
            (new Product())
                ->setName('Second')
                ->setAmount(159.99)
                ->setStore($store)
        );

        $manager->flush();
    }

    /** @psalm-return array<string> */
    public function getDependencies(): array
    {
        return [
            StoreDataFixture::class,
        ];
    }
}
