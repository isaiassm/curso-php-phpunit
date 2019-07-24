<?php

namespace PaymentBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;

class GatewayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotPayWhenAuthenticationFail()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
                ->will($this->returnCallback(
                    function($method, $address, $body){
                     return $this->fakeHttpCLientSend($method, $address, $body);
                    }
                ));

        $logger = $this->createMock(LoggerInterface::class);

        
        $user = 'test';
        $password =  'invalid password';  
        $gateway = new Gateway($httpClient,  $logger, $user, $password);

         $paid = $gateway->pay(
            'Joao carneiro',
            5226516516515161651,
            new \DateTime('now'),
            100
        );

        $this->assertEquals(false, $paid);

    }

    /**
     * @test
     */
    public function shouldNotPayFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
                ->will($this->returnCallback(
                    function($method, $address, $body){
                     return $this->fakeHttpCLientSend($method, $address, $body);
                    }
                ));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password =  'valid password';        
        $gateway = new Gateway($httpClient,  $logger, $user, $password);

         $paid = $gateway->pay(
            'Joao carneiro',
            5226516516515161651,
            new \DateTime('now'),
            100
        );

        $this->assertEquals(false, $paid);

    }

    /**
     * @test
     */
    public function shouldSucessfullyPayWhengatewayReturnOk()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
                ->will($this->returnCallback(
                    function($method, $address, $body){
                     return $this->fakeHttpCLientSend($method, $address, $body);
                    }
                ));

        $logger = $this->createMock(LoggerInterface::class);

        $user = 'test';
        $password =  'valid password';        
        $gateway = new Gateway($httpClient, $logger, $user, $password);

         $paid = $gateway->pay(
            'Joao carneiro',
            9999999999999999,
            new \DateTime('now'),
            100
        );

        $this->assertEquals(true, $paid);

    }



    public function fakeHttpCLientSend($method, $address, $body)
    {
        switch ($address){
            case Gateway::Base_URL . '/authenticate':

            if ($body['password'] != 'valid-password')
            {
                return null;
            }
                return 'meu-token';
                break;

            case Gateway::BASE_URL  . '/pay':
                if($body['credit_card_number'] == 9999999999999999)
                {
                    return ['paid' => true];
                }

                return ['paid' => false];
                break;    
        }
    }
}