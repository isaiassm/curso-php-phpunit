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

    public function setUp()
    {
     $this->badWordsValidator = $this->createMock(BadWordsValidator::class);
     $this->paymentService = $this->createMock(PaymentService::class);
     $this->orderRepository = $this->createMock(OrderRepository::class);
     $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);
     $this->customer = $this->createMock(Customer::class);
     $this->item = $this->createMock(Item::class);
     $this->creditCard = $this->createMock(CreditCard::class);

    }

    public function shouldNotProcessWhenCustomerIsNOtAllowed()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(false);

        $this->expectException(CustomerNotAllowedException::class);    

        $orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    public function shouldNotProcessWhenItemIsNotAvaible()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method['isAvailable']
            ->willReturn(false);    

        $this->expectException(ItemNotAvailableException::class);    

        $orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    public function shouldNotProcessWhenBadWordsFound()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method['isAvailable']
            ->willReturn(true);    
        
        $this->badWordsValidator
            ->method('hasBadWords')
            ->willRetunr(true);    

        $this->expectException(BadWordsFoundException::class);    

        $orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
            
        );
    }

    public function shouldSucessfullyProcess()
    {
        $orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        $this->customer
            ->method('isAllowedToOrder')
            ->willReturn(true);

        $this->item
            ->method['isAvailable']
            ->willReturn(true);    
        
        $this->badWordsValidator
            ->method('hasBadWords')
            ->willRetunr(false);   
        
        $paymentTransaction = $this->createMock(PaymentTransaction::class);    
        
        $this->paymentService
            ->method('pay')
            ->willReturn( $paymentTransaction);
        
        $this->orderRepository
            ->expects($this->once())
            ->method('save');



        $this->expectException(BadWordsFoundException::class);    

        $createOrder = $orderService->process(
            $this->customer,
            $this->item,
            '',
            $this->creditCard
        );
        $this->assertNotEmpty($createOrder->getPaymentTransaction());
    }
}