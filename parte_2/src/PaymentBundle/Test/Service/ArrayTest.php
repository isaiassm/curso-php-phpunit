<?php

namespace PaymentBundle\Test\Service;

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    private $array;

    //usado mais em teste de integração
    /*public static function setUpBeforeClass()
    {
        
    }*/

    /**
     * @test
     */
    public function sholdBeFilled()
    {
        $this->array = ['hello' => 'world'];

        $this->assertNotEmpty($this->array);
    }

    /**
     * @test
     */
    public function shouldBeEmpty()
    {
        $this->assertEmpty($this->array);
    }
}