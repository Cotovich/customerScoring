<?php

namespace App\Command;

use App\Repository\CustomerRepository;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:score-customers',
    description: 'Score Customers',
)]
class ScoreCustomersCommand extends Command
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly ScoringService $scoringService,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'customer id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $customerId = $input->getArgument('id');

        $customers = [];

        if ($customerId) {
            $customers[] = $this->customerRepository->find($customerId);
        } else {
            $customers = $this->customerRepository->findAll();
        }
        if (0 === count($customers)) {
            $io->error('No customers found');

            return Command::FAILURE;
        }
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                $customer->setScore(
                    $this->scoringService::makeScoring(
                        $customer->getPhone(),
                        $customer->getEmail(),
                        $customer->getEducation(),
                        $customer->pdProcessingPermission()
                    )
                );
                $this->entityManager->persist($customer);
                $io->note(
                    sprintf(
                        'Customer %s %s score updated. Scoring: total %s phone %s, email %s, education %s, pd processing permission %s',
                        $customer->getName(),
                        $customer->getSurname(),
                        $customer->getScore(),
                        $this->scoringService::scorePhoneOperator($customer->getPhone()),
                        $this->scoringService::scoreMailOperator($customer->getEmail()),
                        $this->scoringService::scoreEducation($customer->getEducation()),
                        $this->scoringService::scorePdProcessingPermission($customer->pdProcessingPermission()),
                    ));
            }
        }
        $this->entityManager->flush();

        $io->success('Customers scored successfully');

        return Command::SUCCESS;
    }
}
