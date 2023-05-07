<?php

namespace App\Tests\Integration;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    protected DocumentManager $documentManager;
    protected Application $application;

    public function setUp(): void
    {
        parent::setUp();
        $kernel = self::bootKernel();
        $this->documentManager = $kernel->getContainer()
            ->get('doctrine_mongodb')
            ->getManager('default');
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
    }

    public function loadFixtures(): void
    {
        $this->application->run(new ArrayInput([
            'command' => 'doctrine:mongodb:fixtures:load',
            '--no-interaction' => 1,
            '--quiet' => 1,
        ]));
    }
}
