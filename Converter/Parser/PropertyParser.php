<?php
namespace RS\DiExtraBundle\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\PropertyProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;

class PropertyParser
{
    /** @var \ReflectionProperty  */
    protected $reflectionProperty;
    /** @var AnnotationReader  */
    protected $annotationReader;

    public function __construct(AnnotationReader $annotationReader, \ReflectionProperty $reflectionProperty)
    {
        $this->annotationReader = $annotationReader;
        $this->reflectionProperty = $reflectionProperty;
    }

    /**
     * @param ClassMeta $classMeta
     * @return ClassMeta
     */
    public function parse(ClassMeta $classMeta)
    {
        foreach ($this->annotationReader->getPropertyAnnotations($this->reflectionProperty) as $annotation){
            if($annotation instanceof PropertyProcessorInterface){
                $annotation->handleProperty($classMeta, $this->reflectionProperty);
            }
        }
    }

}
