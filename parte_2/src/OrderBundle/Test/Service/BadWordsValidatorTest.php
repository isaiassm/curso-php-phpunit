<?php

namespace OrderBundle\Test\Service;


use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    //Stub é quando voce utiliza uma classe falsa apenas para passar o teste e passar os parametros desejados
    // utiliza-se dataProvider quando faz varias condições de asserção como verdadeiro e falso e outros cenarios de asserções
    /**
     * @test
     * @dataProvider badWordsDataProvider
     */
    public function hasBadWords($badWordsList, $text, $foundBadWords)
    {
        $badWordsRepository = $this->createMock(BadWordsRepository::class);

        $badWordsRepository->method('findAllAsArray')->willReturn($badWordsList);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        $hasBadWords = $badWordsValidator->hasBadWords($text);

        $this->assertEquals($foundBadWords, $hasBadWords);
    }

    public function badWordsDataProvider()
    {
        return [
          'shoulFindWhenHasBadWords' => [
            'badWordsList' => ['bobo', 'chule', 'besta'],
            'text' => 'Seu restaurante e bobo',
            'foundBadWords' => true
          ] ,
          'shoulFindWhenHasNoBadWords' => [
            'badWordsList' => ['bobo', 'chule', 'besta'],
            'text' => 'Seu restaurante ',
            'foundBadWords' => false
          ] , 
          'shoulFindWhenTextEmpty' => [
            'badWordsList' => ['bobo', 'chule', 'besta'],
            'text' => ' ',
            'foundBadWords' => false
          ] , 
          'shoulFindWhenBadWordsListIsEmpty' => [
            'badWordsList' => [],
            'text' => 'Seu restaurante e bobo',
            'foundBadWords' => false
          ] 
        ];
    }
}
