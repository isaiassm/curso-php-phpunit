<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{

    private $gateway;
    private $paymentTransactionRepository;
    private $paymentsService;
    private $customer;
    private $item;
    private $CreditCard;

    //utilizado na maioria dos teste, deve-se trazer todo material que seja replicado para o setUp, exeto cenarios.
    public function setUp():void
    {
        $this->gateway = $this->createMock(Gateway::class);
        $this->paymentTransactionRepository = $this->createMock(PaymentTransactionRepository::class);
        $this->paymentsService = new PaymentService($this->gateway, $this->paymentTransactionRepository);

        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->CreditCard = $this->createMock(CreditCard::class);
    }

  

    /**
     * @test
     */
    public function shouldSaveWhenReturnOk()
    {


        $this->gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $this->paymentTransactionRepository
                ->expects($this->once())
                ->method('save');    

        $this->paymentsService->pay($this->customer , $this->item, $this->CreditCard);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGatewayFails()
    {
        

        $this->gateway
            ->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, false
            ));

        $this->paymentTransactionRepository
                ->expects($this->never())
                ->method('save'); 
                
        $this->expectException(PaymentErrorException::class);        

        

        $this->paymentsService->pay($this->customer , $this->item, $this->CreditCard);
    }

    public function tearDown():void
    {
        unset($this->gateway);
    }
}
