<?php
namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase {
    
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expecteResult){

            $notEmptyValidator = new NotEmptyValidator($value);
    
            $isValid = $notEmptyValidator->isValid();
    
            $this->assertEquals($expecteResult ,$isValid);  
           
    }

    public function valueProvider ()
    {
        return [
            'shouldBeValidWhenValueIsNotEmpty' => ['value' => 'foo', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false]

        ];
    }
}
