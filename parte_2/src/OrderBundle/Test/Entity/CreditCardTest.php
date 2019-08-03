<?php

namespace OrderBundle\Entity;

use PHPUnit\Framework\TestCase;
use OrderBundle\Entity\CreditCard;

class CreditCardTest extends TestCase
{
    /**
     * @test
     * @dataProvider CreditCardDataprovider
     */
    public function CreditReceivedNumber($number, $dateTime, $expectedAllowed )
    {
        $dateTime =  new \DateTime('now');
        $number = 123456;
        $creditCard = new CreditCard(
            $number,
            $dateTime 
        );

        $isAllowed = $creditCard->CreditReceivedNumber();
        $this->assertEquals($expectedAllowed, $isAllowed);
    } 
    
    public function CreditCardDataprovider()
    {
        return[
            'shouldBeAllowedTrue' => [
                'number' => true,
                'dateTime' => false,
                'expectedAllowed' => true
            ]
        ];
    }
}