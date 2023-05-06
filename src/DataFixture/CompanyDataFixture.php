<?php

namespace App\DataFixture;

use App\Document\EmbeddedDocument\Address;
use App\Document\EmbeddedDocument\Company;
use App\Document\EmbeddedDocument\Registration;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CompanyDataFixture implements ODMFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            (new Company())
                ->setName('CompanyName')
                ->setSiret('12345')
                ->setActive(true)
                ->setRegistration(
                    (new Registration())
                    ->setCity('Paris')
                    ->setDate(new \DateTime('2023-04-25'))
                )
                ->addAddress(
                    (new Address())
                        ->setStreetNumber('12')
                        ->setStreetName('de Paris')
                        ->setCity('Paris')
                        ->setZipCode('75001')
                )
        );

        $manager->flush();
    }
}
