<?php

namespace App\Command;

use App\Document\SecureDocument\Trace;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'app:trace:find',
    description: 'Show trace document content',
)]
class TraceCommand extends Command
{
    public function __construct(protected DocumentManager $documentManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $traces = $this->documentManager->getRepository(Trace::class)->findAll();

            if (0 === count($traces)) {
                $io->info('Trace document is empty');

                return Command::SUCCESS;
            }

            foreach ($traces as $trace) {
                $io->info(
                    sprintf('username : "%s" IP address : "%s"', $trace->getUsername(), $trace->getIpAddress())
                );
            }

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $io->error(sprintf('An error occurred when processing import. %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
