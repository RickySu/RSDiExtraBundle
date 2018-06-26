<?php
namespace RS\DiExtraBundle\Tests\Converter;

use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\DefinitionConverter;
use RS\DiExtraBundle\Tests\BaseTestCase;
use RS\DiExtraBundle\Tests\BaseTestTrait;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Definition;

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

    public function test_convertDefinition_null_id()
    {
        //arrange
        $classMeta = new ClassMeta();
        $convertDefinition = new DefinitionConverter();

        //act
        $result = $this->callObjectMethod($convertDefinition, 'convertDefinition', $classMeta);

        //assert
        $this->assertNull($result);
    }

    public function test_convertDefinition_not_match_environment()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = 'debug';
        $definitionConverter = $this->getMockBuilder(DefinitionConverter::class)
            ->setMethods(array('isEnabledInEnvironment'))
            ->getMock();
        $definitionConverter
            ->expects($this->once())
            ->method('isEnabledInEnvironment')
            ->willReturn(false);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'convertDefinition', $classMeta);

        //assert
        $this->assertNull($result);
    }

    public function test_convertDefinition_no_parent()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = 'debug';
        $definitionConverter = $this->getMockBuilder(DefinitionConverter::class)
            ->setMethods(array('isEnabledInEnvironment'))
            ->getMock();
        $definitionConverter
            ->expects($this->once())
            ->method('isEnabledInEnvironment')
            ->willReturn(true);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'convertDefinition', $classMeta);

        //assert
        $this->assertEquals(Definition::class, get_class($result));
    }

    public function test_convertDefinition_has_parent()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = 'debug';
        $classMeta->parent = 'test';
        $definitionConverter = $this->getMockBuilder(DefinitionConverter::class)
            ->setMethods(array('isEnabledInEnvironment'))
            ->getMock();
        $definitionConverter
            ->expects($this->once())
            ->method('isEnabledInEnvironment')
            ->willReturn(true);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'convertDefinition', $classMeta);

        //assert
        $this->assertEquals(ChildDefinition::class, get_class($result));
    }

    public function test_convertDefinition()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->id = 'debug';
        $classMeta->shared = true;
        $classMeta->public = true;
        $classMeta->private = true;
        $classMeta->decorates = 'foo';
        $classMeta->decorationInnerName = 'bar';
        $classMeta->decorationPriority = 10;
        $classMeta->deprecated = true;
        $classMeta->abstract = true;
        $classMeta->tags = array('a', 'b', 'c');
        $classMeta->arguments = array('foo', 'bar');
        $classMeta->methodCalls = array(array('foo', array('foo', 'bar')));
        $classMeta->properties = array('bar', 'foo');
        $classMeta->environments = array('prod', 'dev');
        $classMeta->autowire = true;
        $classMeta->factoryMethod = array('foo', 'bar');
        $classMeta->synthetic = true;
        $classMeta->lazy = true;
        $classMeta->autoconfigured = true;

        $definitionConverter = $this->getMockBuilder(DefinitionConverter::class)
            ->setMethods(array('isEnabledInEnvironment'))
            ->getMock();
        $definitionConverter
            ->expects($this->once())
            ->method('isEnabledInEnvironment')
            ->with($classMeta->environments)
            ->willReturn(true);

        //act
        /** @var Definition $result */
        $result = $this->callObjectMethod($definitionConverter, 'convertDefinition', $classMeta);

        //assert
        $this->assertEquals($classMeta->shared, $result->isShared());
        $this->assertEquals(array($classMeta->decorates, $classMeta->decorationInnerName, $classMeta->decorationPriority), $result->getDecoratedService());
        $this->assertEquals($classMeta->deprecated, $result->isDeprecated());
        $this->assertEquals($classMeta->abstract, $result->isAbstract());
        $this->assertEquals($classMeta->tags, $result->getTags());
        $this->assertEquals($classMeta->arguments, $result->getArguments());
        $this->assertEquals($classMeta->methodCalls, $result->getMethodCalls());
        $this->assertEquals($classMeta->properties, $result->getProperties());
        $this->assertEquals($classMeta->autowire, $result->isAutowired());
        $this->assertEquals($classMeta->autoconfigured, $result->isAutoconfigured());
        $this->assertEquals($classMeta->factoryMethod, $result->getFactory());
        $this->assertEquals($classMeta->synthetic, $result->isSynthetic());
        $this->assertEquals($classMeta->lazy, $result->isLazy());
    }

    public function test_isEnabledInEnvironment_match()
    {
        //arrange
        $allowEnvironments = array('prod', 'dev');
        $environment = 'dev';
        $definitionConverter = new DefinitionConverter();
        $this->setObjectAttribute($definitionConverter, 'environment', $environment);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'isEnabledInEnvironment', $allowEnvironments);

        //assert
        $this->assertTrue($result);
    }

    public function test_isEnabledInEnvironment_not_match()
    {
        //arrange
        $allowEnvironments = array('prod', 'dev');
        $environment = 'test';
        $definitionConverter = new DefinitionConverter();
        $this->setObjectAttribute($definitionConverter, 'environment', $environment);

        //act
        $result = $this->callObjectMethod($definitionConverter, 'isEnabledInEnvironment', $allowEnvironments);

        //assert
        $this->assertFalse($result);
    }

    public function test_convert()
    {
        //arrange
        $classFile = 'foo.php';
        $classMeta = new ClassMeta();
        $classMeta->id = 'foo';
        $classMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->id = 'bar';
        $classMeta->nextClassMeta->nextClassMeta = new ClassMeta();
        $classMeta->nextClassMeta->nextClassMeta->id = 'buz';

        $definition0 = new Definition();
        $definition1 = new Definition();
        $definition2 = new Definition();

        $definitionConverter = $this->getMockBuilder(DefinitionConverter::class)
            ->setMethods(array('parseClassFile', 'convertDefinition'))
            ->getMock();

        $definitionConverter
            ->expects($this->once())
            ->method('parseClassFile')
            ->with($classFile)
            ->willReturn($classMeta);

        $definitionConverter
            ->expects($this->at(1))
            ->method('convertDefinition')
            ->with($classMeta)
            ->willReturn(null);
        $definitionConverter
            ->expects($this->at(2))
            ->method('convertDefinition')
            ->with($classMeta->nextClassMeta)
            ->willReturn($definition1);
        $definitionConverter
            ->expects($this->at(3))
            ->method('convertDefinition')
            ->with($classMeta->nextClassMeta->nextClassMeta)
            ->willReturn($definition2);
        $definitionConverter
            ->expects($this->exactly(3))
            ->method('convertDefinition')
            ;

        //act
        $result = $definitionConverter->convert($classFile);

        //assert
        $this->assertEquals(array('bar', 'buz'), array_keys($result));
        $this->assertSame($definition1, $result['bar']);
        $this->assertSame($definition2, $result['buz']);
    }
}
