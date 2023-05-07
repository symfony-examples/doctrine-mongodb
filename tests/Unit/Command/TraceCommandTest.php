<?php

namespace App\Tests\Unit\Command;

use App\Command\TraceCommand;
use App\Document\SecureDocument\Trace;
use App\Exception\RuntimeScriptException;
use App\Manager\TraceManager;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class TraceCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private TraceManager|MockObject $traceManager;

    protected function setUp(): void
    {
        $this->traceManager = $this->createMock(TraceManager::class);
        $application = new Application();
        $application->add(new TraceCommand($this->traceManager));
        $command = $application->find('app:trace:find');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @psalm-param array<Trace> $traces
     *
     * @dataProvider dataProvider
     */
    public function testExecuteWithSuccess(array $traces, string $messageDisplay, int $statusCode): void
    {
        $this->traceManager
            ->expects($this->once())
            ->method('findAll')
            ->willReturn(
                $traces
            );

        $this->commandTester->execute([]);

        $this->assertEquals(
            $messageDisplay,
            trim($this->commandTester->getDisplay())
        );
        $this->assertSame($statusCode, $this->commandTester->getStatusCode());
    }

    public function testExecuteWithError()
    {
        $this->traceManager
            ->expects($this->once())
            ->method('findAll')
            ->willThrowException(
                new RuntimeScriptException('Exception message')
            );

        $commandTester = $this->commandTester;
        $commandTester->execute([]);

        $this->assertEquals(
            '[ERROR] An error occurred when processing import. Exception message',
            trim($commandTester->getDisplay())
        );
        $this->assertSame(1, $commandTester->getStatusCode());
    }

    /** @psalm-return iterable<array{array<Trace>, string, int}> */
    public static function dataProvider(): iterable
    {
        yield 'TracesNotEmpty' => [
            (new ArrayCollection([
                (new Trace())
                    ->setIpAddress('1.2.3.4')
                    ->setUsername('user name'),
            ]))->toArray(),
            '[INFO] username : "user name" IP address : "1.2.3.4"',
            0,
        ];
        yield 'TracesIsEmpty' => [
            [],
            '[INFO] Trace document is empty',
            0,
        ];
    }
}
