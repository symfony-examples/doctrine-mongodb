<?php

namespace App\Tests\Integration\Manager;

use App\Document\EmbeddedDocument\Address;
use App\Document\EmbeddedDocument\Company;
use App\Document\EmbeddedDocument\Registration;
use App\Manager\CompanyManager;
use App\Tests\Integration\AbstractKernelTestCase;

class CompanyManagerTest extends AbstractKernelTestCase
{
    private CompanyManager $companyManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->companyManager = new CompanyManager($this->documentManager);
    }

    public function testFindAll()
    {
        $this->loadFixtures();

        $companies = $this->companyManager->findAll();
        self::assertCount(1, $companies);
        self::assertIsIterable($companies);

        foreach ($companies as $company) {
            self::assertInstanceOf(Company::class, $company);
            self::assertInstanceOf(Registration::class, $company->getRegistration());
            self::assertIsIterable($addresses = $company->getAddresses());
            self::assertCount(2, $addresses);

            foreach ($addresses as $address) {
                self::assertInstanceOf(Address::class, $address);
            }
        }
    }
}
