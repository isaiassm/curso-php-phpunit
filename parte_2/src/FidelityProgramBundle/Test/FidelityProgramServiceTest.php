<?php

namespace FidelityProgramBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use FidelityProgramBundle\Repository\PointsRepository;
use FidelityProgramBundle\Service\PointsCalculator;
use OrderBundle\Entity\Customer;
use FidelityProgramBundle\Service\FidelityProgramService;

class FidelityProgramServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSaveWhenReceveidPoints()
    {
        $pointsRepository = $this->createMock(PointsRepository::class);

        //neste teste utilzamos o mock que consiste em fazer asserção no comportamento do objeto
        $pointsRepository->expects($this->once())
                ->method('save');
        
        $pointsCalculator = $this->createMock(PointsCalculator::class);

        $pointsCalculator->method('calculatePointsToReceive')->willReturn(100);
    

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointsCalculator);

        $customer = $this->createMock(Customer::class);
        $value = 50;
        $fidelityProgramService->addPoints($customer, $value);

    }
    public function shouldSaveWhenReceveidZeroPoints()
    {
        $pointsRepository = $this->createMock(PointsRepository::class);

        //neste teste utilzamos o mock que consiste em fazer asserção no comportamento do objeto
        $pointsRepository->expects($this->never())
                ->method('save');
        
        $pointsCalculator = $this->createMock(PointsCalculator::class);

        $pointsCalculator->method('calculatePointsToReceive')->willReturn(0);
    

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointsCalculator);

        $customer = $this->createMock(Customer::class);
        $value = 20;
        $fidelityProgramService->addPoints($customer, $value);

    }
}