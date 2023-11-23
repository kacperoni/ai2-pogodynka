<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    /** @dataProvider dataGetFahrenheit */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();

        $this->assertSame((float)$expectedFahrenheit, $measurement->setCelsius($celsius)->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0.5', 32.9],
            ['12.5', 54.5],
            ['-30', -22],
            ['11.58', 52.844],
            ['374821.123123', 674710.0216214],
            ['-122.32', -188.176],
            ['18', 64.4]
        ];
    }
}
