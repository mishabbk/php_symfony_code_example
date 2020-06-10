<?php

namespace App\Command\Enterprise;

use App\Repository\Enterprise\EnterpriseRepository;
use App\Service\Enterprise\EnterpriseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InsuranceCheckCommand extends Command
{
    /**
     * @var EnterpriseService
     */
    private $enterpriseService;

    /**
     * @var EnterpriseRepository
     */
    private $enterpriseRepository;

    /**
     * InsuranceCheckCommand constructor.
     */
    public function __construct(EnterpriseService $enterpriseService, EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseService = $enterpriseService;
        $this->enterpriseRepository = $enterpriseRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('enterprise:insurance:check')
            ->setDescription('Update all the Enterprise insurance state')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $enterprises = $this->enterpriseRepository->findAll();
        foreach ($enterprises as $enterprise) {
            $this->enterpriseService->updateInsuranceState($enterprise);

            $output->writeln(
                sprintf(
                    '<info>%s insurance state has been updated to %s</info>',
                    $enterprise->getCompany()->getName(),
                    $enterprise->getInsuranceState()
                )
            );
        }

        return 0;
    }
}
