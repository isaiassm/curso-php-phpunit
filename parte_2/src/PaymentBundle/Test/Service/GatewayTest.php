<?php

namespace PaymentBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use MyFramework\HttpClientInterface;
use PaymentBundle\Service\Gateway;
use MyFramework\LoggerInterface;

class GatewayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotPayWhenAuthenticationFail()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        
        $logger = $this->createMock(LoggerInterface::class);

        $name = 'Joao carneiro';
        $CreditCardNumber = 5226516516515161651;
        $user = 'test';
        $value = 100;
        $dateTime =  new \DateTime('now');
        $password =  'invalid password';  
        $gateway = new Gateway($httpClient,  $logger, $user, $password);

        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                 'user' =>  $user,
                 'password' => $password 
                ],
                 null
            ]

        ];
        
        $httpClient
                ->expects($this->once())
                ->method('send')
                ->will($this->returnValueMap($map));
                
         $paid = $gateway->pay(
            $name,
            $CreditCardNumber,
            $dateTime,
            $value
        );

        $this->assertEquals(false, $paid);

    }
        
     /**
     * @test
     */
    public function shouldNotPayFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);

        $validity = new \DateTime('now');

        $logger = $this->createMock(LoggerInterface::class);

        
        $token = 'meu-token';
        $user = 'test';
        $password =  'valid password';        
        $gateway = new Gateway($httpClient,  $logger, $user, $password);



        $httpClient
                ->expects($this->at(0))
                ->method('send')
                ->willReturn( $token);
            
        $httpClient
                ->expects($this->at(1))
                ->method('send')
                ->willReturn(['paid' => false]);

        $name = 'Joao carneiro';
        $CreditCardNumber =  5226516516515161651;
        $validity = new \DateTime('now');
        $value = 100;    
        $paid = $gateway->pay(
            $name,
            $CreditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid);

    }


     /**
     * @test
     */
    public function shouldSucessfullyPayWhengatewayReturnOk()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $validity = new \DateTime('now');

        $logger = $this->createMock(LoggerInterface::class);

        $name = 'Joao carneiro';
        $CreditCardNumber = 9999999999999999;
        $value = 100;
        $user = 'test';
        $token = 'meu-token';
        $password =  'valid password';        
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                 'user' => $user,
                 'password' => $password
                ],
                 'meu-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' =>   $CreditCardNumber,
                    'validity' =>  $validity,
                    'value' =>  $value,
                    'token' =>   $token
                ],
                ['paid' => true]
            ]

        ];

        $httpClient
                ->expects($this->atLeast(2))
                ->method('send')
                ->will($this->returnValueMap($map));
        

         $paid = $gateway->pay(
            $name,
            $CreditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(true, $paid);

    }
}