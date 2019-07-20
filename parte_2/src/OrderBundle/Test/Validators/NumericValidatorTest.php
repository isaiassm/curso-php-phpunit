<?php
namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase {
    
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expecteResult){

            $NumericValidator = new NumericValidator($value);
    
            $isValid = $NumericValidator->isValid();
    
            $this->assertEquals($expecteResult ,$isValid);  
           
    }

    public function valueProvider ()
    {
        return [
            'shouldBeValidWhenValueIsANumericString' => ['value' => 15, 'expectedResult' => true],
            'shouldBeValidWhenValueIsNumber' => ['value' => '20', 'expectedResult' => true],
            'shouldBeValidWhenValueIsNotANumber' => ['value' => 'teste', 'expectedResult' => false],
            'shouldBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false]


        ];
    }
}
