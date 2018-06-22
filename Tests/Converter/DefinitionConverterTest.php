<?php
namespace RS\DiExtraBundle\Tests\Converter;

use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Converter\DefinitionConverter;
use RS\DiExtraBundle\Tests\BaseTestCase;
use RS\DiExtraBundle\Tests\BaseTestTrait;

class DefinitionConverterTest extends BaseTestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function test_getClassName_no_namespace()
    {
        //arrange
        $file = __DIR__.'/../bootstrap.php';
        $definitionConverter = new DefinitionConverter();

        //act
        $this->callObjectMethod($definitionConverter, 'getClassName', $file);

        //assert
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_getClassName_no_class_or_trait()
    {
        //arrange
        $reflectionClass = new \ReflectionClass(ClassProcessorInterface::class);
        $definitionConverter = new DefinitionConverter();

        //act
        $this->callObjectMethod($definitionConverter, 'getClassName', $reflectionClass->getFileName());

        //assert
    }

    public function test_getClassName_trait()
    {
        //arrange
        $reflectionClass = new \ReflectionClass(BaseTestTrait::class);
        $definitionConverter = new DefinitionConverter();

        //act
        $result = $this->callObjectMethod($definitionConverter, 'getClassName', $reflectionClass->getFileName());

        //assert
        $this->assertEquals(BaseTestTrait::class, $result);
    }

    public function test_getClassName_class()
    {
        //arrange
        $reflectionClass = new \ReflectionClass(self::class);
        $definitionConverter = new DefinitionConverter();

        //act
        $result = $this->callObjectMethod($definitionConverter, 'getClassName', $reflectionClass->getFileName());

        //assert
        $this->assertEquals(self::class, $result);
    }

    public function test_getReflectionClass()
    {
        //arrange
        $definitionConverter = new DefinitionConverter();
        $reflectionClass = new \ReflectionClass(DefinitionConverter::class);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'getReflectionClass', $reflectionClass->getFileName());

        //assert
        $this->assertEquals($reflectionClass->getFileName(), $result->getFileName());
    }
}