<?php
namespace RS\DiExtraBundle\Tests\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\MethodProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Parser\MethodParser;
use RS\DiExtraBundle\Tests\BaseTestCase;

class MethodParserTest extends BaseTestCase
{
    public function test___construct()
    {
        //arrange
        $annotationReader = new AnnotationReader();
        $reflectionMethod = new \ReflectionMethod(self::class, '__construct');

        //act
        $parser = new MethodParser($annotationReader, $reflectionMethod);

        //assert
        $this->assertSame($annotationReader, $this->getObjectAttribute($parser, 'annotationReader'));
        $this->assertSame($reflectionMethod, $this->getObjectAttribute($parser, 'reflectionMethod'));
    }

    public function test_parse()
    {
        //arrange
        $classMeta = new ClassMeta();

        $reflectionMethod = $this->getMockBuilder(\ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $annotations = array(
            $this->getMockBuilder(MethodProcessorInterface::class)->onlyMethods(array('handleMethod'))->getMockForAbstractClass(),
            new \stdClass(),
            $this->getMockBuilder(MethodProcessorInterface::class)->onlyMethods(array('handleMethod'))->getMockForAbstractClass(),
        );

        $annotations[0]
            ->expects($this->once())
            ->method('handleMethod')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionMethod $reflectionMethodForTest) use($classMeta, $reflectionMethod){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionMethod, $reflectionMethodForTest);
            })
        ;

        $annotations[2]
            ->expects($this->once())
            ->method('handleMethod')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionMethod $reflectionMethodForTest) use($classMeta, $reflectionMethod){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionMethod, $reflectionMethodForTest);
            })
        ;

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array('getMethodAnnotations'))
            ->getMock();

        $annotationReader
            ->expects($this->once())
            ->method('getMethodAnnotations')
            ->with($reflectionMethod)
            ->willReturn($annotations);

        $methodParser = $this->getMockBuilder(MethodParser::class)
            ->disableOriginalConstructor()
            ->onlyMethods(array())
            ->getMock();

        $this->setObjectAttribute($methodParser, 'reflectionMethod', $reflectionMethod);
        $this->setObjectAttribute($methodParser, 'annotationReader', $annotationReader);

        //act
        $this->callObjectMethod($methodParser, 'parse', $classMeta);

        //assert
    }
}
