<?php
namespace RS\DiExtraBundle\Tests\Converter;


use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ClassMetaTest extends BaseTestCase
{

    public function test_findFactoryClassMeta_not_found()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta = new ClassMeta();
        $reflectionMethod = new \ReflectionMethod($this, 'test_findFactoryClassMeta_not_found');

        //act
        $result = $classMeta->findFactoryClassMeta($reflectionMethod);

        //assert
        $this->assertFalse($result);
    }

    public function test_findFactoryClassMeta()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta->factoryClass = self::class;
        $classMeta->nextClassMeta->nextClassMeta->factoryMethod = array(self::class, 'test_findFactoryClassMeta_not_found');
        $reflectionMethod = new \ReflectionMethod($this, 'test_findFactoryClassMeta_not_found');

        //act
        $result = $classMeta->findFactoryClassMeta($reflectionMethod);

        //assert
        $this->assertSame($classMeta->nextClassMeta->nextClassMeta, $result);
    }
}