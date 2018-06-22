<?php
namespace RS\DiExtraBundle\Tests\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\PropertyProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Parser\PropertyParser;
use RS\DiExtraBundle\Tests\BaseTestCase;

class PropertyParserTest extends BaseTestCase
{
    public function test___construct()
    {
        //arrange
        $annotationReader = new AnnotationReader();
        $reflectionProperty = $this->getMockBuilder(\ReflectionProperty::class)->disableOriginalConstructor()->getMock();

        //act
        $parser = new PropertyParser($annotationReader, $reflectionProperty);

        //assert
        $this->assertSame($annotationReader, $this->getObjectAttribute($parser, 'annotationReader'));
        $this->assertSame($reflectionProperty, $this->getObjectAttribute($parser, 'reflectionProperty'));
    }

    public function test_parse()
    {
        //arrange
        $classMeta = new ClassMeta();

        $reflectionProperty = $this->getMockBuilder(\ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();

        $annotations = array(
            $this->getMockBuilder(PropertyProcessorInterface::class)->setMethods(array('handleProperty'))->getMockForAbstractClass(),
            new \stdClass(),
            $this->getMockBuilder(PropertyProcessorInterface::class)->setMethods(array('handleProperty'))->getMockForAbstractClass(),
        );

        $annotations[0]
            ->expects($this->once())
            ->method('handleProperty')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionProperty $reflectionPropertyForTest) use($classMeta, $reflectionProperty){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionProperty, $reflectionPropertyForTest);
            })
        ;

        $annotations[2]
            ->expects($this->once())
            ->method('handleProperty')
            ->willReturn(function(ClassMeta $classMetaForTest, \ReflectionProperty $reflectionPropertyForTest) use($classMeta, $reflectionProperty){
                $this->assertSame($classMeta, $classMetaForTest);
                $this->assertSame($reflectionProperty, $reflectionPropertyForTest);
            })
        ;

        $annotationReader = $this->getMockBuilder(AnnotationReader::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getPropertyAnnotations'))
            ->getMock();

        $annotationReader
            ->expects($this->once())
            ->method('getPropertyAnnotations')
            ->with($reflectionProperty)
            ->willReturn($annotations);

        $propertyParser = $this->getMockBuilder(PropertyParser::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $this->setObjectAttribute($propertyParser, 'reflectionProperty', $reflectionProperty);
        $this->setObjectAttribute($propertyParser, 'annotationReader', $annotationReader);

        //act
        $this->callObjectMethod($propertyParser, 'parse', $classMeta);

        //assert
    }
}
