<?php

namespace App\DataFixture;

use App\Document\SecureDocument\Trace;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TraceDataFixture implements ODMFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            (new Trace())
            ->setUsername('username')
            ->setIpAddress('0.0.0.0')
        );

        $manager->flush();
    }
}
