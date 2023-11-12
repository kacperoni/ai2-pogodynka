<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use App\Repository\MeasurementRepository;

class WeatherUtil
{
    public function __construct(private readonly MeasurementRepository $repository){}
    /** @return Measurement[] */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->repository->findByLocation($location);
    }

    /** @return Measurement[] */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        return $this->repository->findByCountryCodeAndCity($countryCode, $city);
    }
}