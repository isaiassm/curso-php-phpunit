<?php

namespace OrderBundle\Test\Service;

use PHPUnit\Framework\TestCase;
use OrderBundle\Service\OrderService;
use OrderBundle\Service\BadWordsValidator;
use PaymentBundle\Service\PaymentService;
use OrderBundle\Repository\OrderRepository;
use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Exception\BadWordsFoundException;
use PaymentBundle\Entity\PaymentTransaction;

class OrderServiceTest extends TestCase
{
    private $badWordsValidator;
    private $paymentService;
    private $orderRepository;
    private $fidelityProgramService;
    private $customer;
    private $item;
    private $creditCard;
    private $orderService;

    public function setUp():void
    {
     $this->badWordsValidator = $this->createMock(BadWordsValidator::class);
     $this->paymentService = $this->createMock(PaymentService::class);
     $this->orderRepository = $this->createMock(OrderRepository::class);
     $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);
     $this->customer = $this->createMock(Customer::class);
     $this->item = $this->createMock(Item::class);
     $this->creditCard = $this->createMock(CreditCard::class);

    }

    /**
     * @test
     */
    public function shouldNotProcessWhenCustomerIsNOtAllowed()
    {
        $this->withOrderService()
            ->withCustomerNotAllowed();

        $this->expectException(CustomerNotAllowedException::class);    

        $this->orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenItemIsNotAvaible()
    {
        $this->withOrderService()
            ->withCustomerAloowed()
            ->withNotIsAvailableItem();

  

        $this->expectException(ItemNotAvailableException::class);    

        $this->orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    /**
     * @test
     */
    public function shouldNotProcessWhenBadWordsFound()
    {
        $this->withOrderService()
            ->withCustomerAloowed()
            ->withIsAvailableItem()
            ->withBadWordsFound();  
        
       

        $this->expectException(BadWordsFoundException::class);    

        $this->orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    public function shouldSucessfullyProcess()
    {
        $this->withOrderService()
            ->withCustomerAloowed()
            ->withIsAvailableItem()
            ->withBadWordsFound();
        
        $paymentTransaction = $this->createMock(PaymentTransaction::class);    
        
        $this->paymentService
            ->method('pay')
            ->willReturn( $paymentTransaction);
        
        $this->orderRepository
            ->expects($this->once())
            ->method('save');


        $createOrder = $this->orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
        );
        $this->assertNotEmpty($createOrder->getPaymentTransaction());
    }

    public function withOrderService()
    {
        $this->orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );
        return $this;
    }

    public function withCustomerNotAllowed()
    {
        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(false);
        return $this;
    }

    public function withCustomerAloowed()
    {
        $this->customer
        ->method('isAllowedToOrder')
        ->willReturn(true);

        return $this;
    }

    public function withNotIsAvailableItem()
    {
        $this->item
        ->method('isAvailable')
        ->willReturn(false);    

        return $this;
    }

    public function withIsAvailableItem()
    {
        $this->item
        ->method('isAvailable')
        ->willReturn(true);    

        return $this;
    }

    public function withBadWordsFound()
    {
        $this->badWordsValidator
        ->method('hasBadWords')
        ->willReturn(true);    

        return $this;
    }
    public function withBadWordsNotFound()
    {
        $this->badWordsValidator
        ->method('hasBadWords')
        ->willRetunr(false);    

        return $this;
    }
}