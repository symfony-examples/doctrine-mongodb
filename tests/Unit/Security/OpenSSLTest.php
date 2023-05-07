<?php

namespace App\Tests\Unit\Security;

use App\Security\OpenSSL;
use PHPUnit\Framework\TestCase;

class OpenSSLTest extends TestCase
{
    public function testEncrypt(): void
    {
        self::assertNotFalse(
            base64_decode(OpenSSL::encrypt('username'))
        );
    }

    public function testDecrypt(): void
    {
        self::assertSame(
            'username',
            OpenSSL::decrypt('/l0YRmPEAc+tcROza/Yct0castzH44TwsWIMfmyrJz1ceSsQGxnvLOugvOUz1WgBno4796xicX3yOlspfyB4tQ==')
        );
    }
}
