<?php
namespace RS\DiExtraBundle\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\MethodProcessorInterface;
use RS\DiExtraBundle\Converter\Annotation\ObserveMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

class MethodParser
{
    /** @var \ReflectionMethod  */
    protected $reflectionMethod;
    /** @var AnnotationReader  */
    protected $annotationReader;

    public function __construct(AnnotationReader $annotationReader, \ReflectionMethod $reflectionMethod)
    {
        $this->annotationReader = $annotationReader;
        $this->reflectionMethod = $reflectionMethod;
    }

    /**
     * @param ClassMeta $classMeta
     * @return ClassMeta
     */
    public function parse(ClassMeta $classMeta)
    {
        foreach ($this->annotationReader->getMethodAnnotations($this->reflectionMethod) as $annotation){
            if($annotation instanceof MethodProcessorInterface){
                $annotation->handleMethod($classMeta, $this->reflectionMethod);
            }
        }
    }

}
