<?php

namespace App\Command;

use App\Repository\CustomerRepository;
use App\Service\RandomUserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'data:customer-import',
    description: 'Import customers from random user generator api',
)]
class DataCustomerImportCommand extends Command
{
    public function __construct(
        private RandomUserService $randomUserService,
        private ParameterBagInterface $parameterBag,
        private CustomerRepository $customerRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $minImport = $this->parameterBag->get('random_user_api_min_results');

        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Set number of customers to be imported. (Min: ' . $minImport . ')')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $minImport = $this->parameterBag->get('random_user_api_min_results');
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            if (!is_numeric($arg1)) {
                $io->error('1st argument must be numeric value');
                return Command::INVALID;
            }

            if ($arg1 < $minImport) {
                $io->error('Number of records should not be less than the minimum amount (Min: ' . $minImport . ')');
                return Command::INVALID;
            }

            $minImport = $arg1;

        }

        $users = $this->randomUserService->fetchUsers($minImport);
        $this->customerRepository->storeUsersAsCustomers($users);
        $io->success('Done!');

        return Command::SUCCESS;
    }
}
