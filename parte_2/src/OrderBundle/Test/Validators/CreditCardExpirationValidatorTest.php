<?php
namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase {
    
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expecteResult){

            $CreditCardExpirationDate = new \DateTime($value);

            $CreditCardExpirationValidator = new CreditCardExpirationValidator($CreditCardExpirationDate);
    
            $isValid = $CreditCardExpirationValidator->isValid();
    
            $this->assertEquals($expecteResult ,$isValid);  
           
    }

   
    public function valueProvider ()
    {
        return [
            'shouldBevalidWhenDateIsNotExpired' => ['value' => '2040-01-01', 'expectedResult' => true],
            'shouldBevalidWhenDateIsExpired' => ['value' => '2005-01-01', 'expectedResult' => false],
   
        ];
    }
}
