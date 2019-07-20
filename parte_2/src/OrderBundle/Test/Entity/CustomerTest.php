<?php
namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    
    /**
     * Para utilizar o DataProvider deve-se fazer a notação da funcao
     * @test
     * @dataProvider CustomerAllowedDataProvider
     */
    public function isAllowedToOrder($isActive, $isBlocked, $expectedAllowed )
    {
        $customer = new Customer(
            $isActive,
            $isBlocked,
            'isaias santos',
            '+55119555558888'
        );

        $isAloowed = $customer->isAllowedToOrder();
        
        $this->assertEquals($expectedAllowed, $isAloowed);
    }

    public function CustomerAllowedDataProvider()
    {
        //teste de todas possibilidades de cenarios
        return [
            'shouldBeAllowedWhenIsActiveAndNotBlocked' => [
                'isActive' => true,
                'isBlocked' => false,
                'expectedAllowed' => true
            ],
            'shouldBeAllowedWhenIsActiveButIsBlocked' => [
                'isActive' => true,
                'isBlocked' => true,
                'expectedAllowed' => false
            ],
            'shouldBeAllowedWhenIsNotActive' => [
                'isActive' => false,
                'isBlocked' => false,
                'expectedAllowed' => false
            ],
            'shouldBeAllowedWhenIsNotActiveAndIsBlocked' => [
                'isActive' => false,
                'isBlocked' => true,
                'expectedAllowed' => false
            ]

        ];
    }
}