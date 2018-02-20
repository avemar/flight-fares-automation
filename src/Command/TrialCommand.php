<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\Tester\Tester;

class TrialCommand extends Command
{
    protected static $defaultName = 'app:destinations:test';
    
    private $tester;

    public function __construct(Tester $tester)
    {
        parent::__construct();

        $this->tester = $tester;
    }

    protected function configure()
    {
        $this
            ->setDescription('Selenium tester')
            ->addArgument(
                'test_id',
                InputArgument::REQUIRED,
                'Test ID'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $testId = $input->getArgument('test_id');

        $executed = $this->tester->execute($testId);

        if ($executed) {
            $io->success('Test ID ' . $testId . ' executed.');
            return;
        }

        $io->error('An error happened during test ID ' . $testId . ' execution.');
    }
}
