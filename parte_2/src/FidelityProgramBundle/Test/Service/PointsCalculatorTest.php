<?php

namespace FidelityProgramBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use FidelityProgramBundle\Service\PointsCalculator;

class PointsCalculatorTest extends TestCase
{
    /**
     * @dataProvider valueDataProvader
     */
    public function testPointsToReceive($value, $expectedPoints)
    {
        $pointsCalculator = new PointsCalculator();
        $points = $pointsCalculator->calculatePointsToReceive($value);

        $this->assertEquals($expectedPoints, $points);
    }

    public function valueDataProvader()
    {
        return [
            [30, 0],
            [55, 1100],
            [75, 2250],
            [110, 5500]
        ];
    }
}