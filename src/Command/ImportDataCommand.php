<?php

namespace App\Command;

use App\Helper\ImporterHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-data',
    description: 'import champions data',
)]
class ImportDataCommand extends Command
{

    private ImporterHelper $importerHelper;

    public function __construct(ImporterHelper $importerHelper) {
        $this->importerHelper = $importerHelper;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $totalImported = $this->importerHelper->importOrUpdateLolChampions();

        $io->success(sprintf('%s champions imported', $totalImported));

        return Command::SUCCESS;
    }
    
}
