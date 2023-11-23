<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    private const DEFAULT_FORMAT = 'json';
    private const CSV_FORMAT = 'csv';
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = self::DEFAULT_FORMAT,
        #[MapQueryParameter] bool $twig = false,
    ): Response
    {
        if($format != self::DEFAULT_FORMAT && $format != self::CSV_FORMAT){
            return $this->json('Invalid format provided.', Response::HTTP_BAD_REQUEST);
        }

        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        if($twig){
            return $this->render(sprintf("weather_api/index.%s.twig", $format), [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements
            ]);
        }

        $data = $this->json([
            'measurements' => array_map(static fn(Measurement $measurement) => [
                'date' => $measurement->getDate()->format('Y-m-d'),
                'celsius' => $measurement->getCelsius(),
                'fahrenheit' => $measurement->getFahrenheit()
            ], $measurements)
        ]);

        if($format === self::CSV_FORMAT){
            $data = $this->json(
                implode(PHP_EOL, array_map(
                    static fn(Measurement $measurement) => sprintf(
                        "%s, %s, %s, %s, %s",
                        $measurement->getLocation()->getCity(),
                        $measurement->getLocation()->getCountry(),
                        $measurement->getDate()->format('Y-m-d'),
                        $measurement->getCelsius(),
                        $measurement->getFahrenheit()
                    ), $measurements)
                )
            );
        }

        return $data;
    }
}
