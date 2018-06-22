<?php
namespace RS\DiExtraBundle\Converter\Parser;

use Doctrine\Common\Annotations\AnnotationReader;
use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;

class ClassParser
{
    /** @var \ReflectionClass  */
    protected $reflectionClass;
    /** @var AnnotationReader  */
    protected $annotationReader;

    public function __construct(AnnotationReader $annotationReader, \ReflectionClass $reflectionClass)
    {
        $this->annotationReader = $annotationReader;
        $this->reflectionClass = $reflectionClass;
    }

    /**
     * @param ClassMeta $classMeta
     */
    public function parse(ClassMeta $classMeta)
    {
        $this->parseClass($classMeta);
        $this->parseMethod($classMeta);
        $this->parseProperty($classMeta);
    }

    protected function parseParent(ClassMeta $classMeta)
    {
        if(!($parentClass = $this->reflectionClass->getParentClass())){
            return;
        }
        $classParser = new static($this->annotationReader, $parentClass);
        $classParser->parse($classMeta);
    }

    protected function parseMethod(ClassMeta $classMeta)
    {
        foreach ($this->reflectionClass->getMethods() as $reflectionMethod) {
            $methodParser = new MethodParser($this->annotationReader, $reflectionMethod);
            $methodParser->parse($classMeta);
        }
    }

    protected function parseProperty(ClassMeta $classMeta)
    {
        foreach ($this->reflectionClass->getProperties() as $reflectionMethod) {
            $methodParser = new PropertyParser($this->annotationReader, $reflectionMethod);
            $methodParser->parse($classMeta);
        }
    }

    protected function parseClass(ClassMeta $classMeta)
    {
        $this->parseParent($classMeta);
        foreach($this->annotationReader->getClassAnnotations($this->reflectionClass) as $annotation){
            if($annotation instanceof ClassProcessorInterface){
                $classMeta->class = $this->reflectionClass->getName();
                $annotation->handleClass($classMeta, $this->reflectionClass);
            }
        }
    }
}
