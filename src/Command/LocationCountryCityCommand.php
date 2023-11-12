<?php

namespace App\Command;

use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'location:country-city',
    description: 'Add a short description for your command',
)]
class LocationCountryCityCommand extends Command
{
    public function __construct(private readonly LocationRepository $locationRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::OPTIONAL, 'Location country code')
            ->addArgument('city', InputArgument::OPTIONAL, 'Location city')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $city = $input->getArgument('city');

        $location = $this->locationRepository->findByCountryCodeAndCity($countryCode, $city);

        $io->writeln(
            sprintf("Location: \n\tID: %s\n\tCountry: %s \n\tCity: %s \n\tLatitude: %s \n\tLongitude: %s",
                $location->getId(),
                $location->getCountry(),
                $location->getCity(),
                $location->getLatitude(),
                $location->getLongitude()
            )
        );

        return Command::SUCCESS;
    }
}
