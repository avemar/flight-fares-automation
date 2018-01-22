<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\DestinationsRefresher\DestinationsRefresher;

class AppDestinationsRefreshCommand extends Command
{
    protected static $defaultName = 'app:destinations:refresh';
    
    private $destinationsRefresher;

    public function __construct(DestinationsRefresher $destinationsRefresher)
    {
        $this->destinationsRefresher = $destinationsRefresher;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Refresh destinations for a specific airline')
            ->addArgument(
                'airline_id',
                InputArgument::REQUIRED,
                'Airline id'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $airlineId = $input->getArgument('airline_id');

        $isRefreshed = $this->destinationsRefresher->refresh($airlineId);

        if ($isRefreshed) {
            $io->success('Destinations for airline ' . $airlineId . ' refreshed');
            return;
        }

        $io->error('An error happened during refresh for airline ' . $airlineId);
    }
}
