<?php
namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase {
    
    //Dataprovider utilizado 1 cenario e podendo reutilizar com varias funcoes
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expecteResult){

            $CreditCardNumberValidator = new CreditCardNumberValidator($value);
    
            $isValid = $CreditCardNumberValidator->isValid();
    
            $this->assertEquals($expecteResult ,$isValid);  
           
    }

    /**
     * Teste de cartao de credito
     * Esta com um suposto erro para receber valor numerico
     */
    public function valueProvider ()
    {
        return [
            'shouldBeValidWhenValueIsACreditCard' => ['value' => '5413927572743114', 'expectedResult' => true],
            'shouldBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
            'shouldBeValidWhenValueNot' => ['value' => '1234', 'expectedResult' => false],
   
        ];
    }
}
