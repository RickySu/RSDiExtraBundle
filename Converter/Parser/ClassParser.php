<?php
namespace RS\DiExtraBundle\Converter\Parser;

use Doctrine\Common\Annotations\Reader;
use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Converter\ClassMeta;

class ClassParser
{
    /** @var \ReflectionClass  */
    protected $reflectionClass;
    /** @var Reader  */
    protected $annotationReader;

    public function __construct(Reader $annotationReader, \ReflectionClass $reflectionClass)
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
        $this->createClassParser($parentClass)->parse($classMeta);
    }

    protected function parseMethod(ClassMeta $classMeta)
    {
        foreach ($this->reflectionClass->getMethods() as $reflectionMethod) {
            $this->createMethodParser($reflectionMethod)->parse($classMeta);
        }
    }

    protected function parseProperty(ClassMeta $classMeta)
    {
        foreach ($this->reflectionClass->getProperties() as $reflectionProperty) {
            $this->createPropertyParser($reflectionProperty)->parse($classMeta);
        }
    }

    protected function parseClass(ClassMeta $classMeta)
    {
        $this->parseParent($classMeta);
        $this->parseTraits($classMeta);
        foreach($this->annotationReader->getClassAnnotations($this->reflectionClass) as $annotation){
            if($annotation instanceof ClassProcessorInterface){
                $classMeta->class = $this->reflectionClass->getName();
                $annotation->handleClass($classMeta, $this->reflectionClass);
            }
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @return ClassParser
     */
    protected function createClassParser(\ReflectionClass $reflectionClass)
    {
        return new static($this->annotationReader, $reflectionClass);
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return MethodParser
     */
    protected function createMethodParser(\ReflectionMethod $reflectionMethod)
    {
        return new MethodParser($this->annotationReader, $reflectionMethod);
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @return PropertyParser
     */
    protected function createPropertyParser(\ReflectionProperty $reflectionProperty)
    {
        return new PropertyParser($this->annotationReader, $reflectionProperty);
    }

    protected function parseTraits(ClassMeta $classMeta)
    {
        if(!$this->reflectionClass->getTraits()){
            return;
        }

        $originClassName = $classMeta->class;
        $originId = $classMeta->id;
        foreach($this->reflectionClass->getTraits() as $traitReflection){
            $classParser = new static($this->annotationReader, $traitReflection);
            $classParser->parse($classMeta);
        }
        $classMeta->id = $originId;
        $classMeta->class = $originClassName;
    }

}
