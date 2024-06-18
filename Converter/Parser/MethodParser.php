<?php
namespace RS\DiExtraBundle\Converter\Parser;

use Doctrine\Common\Annotations\Reader;
use Functional\Bundles\Foo\Form\CustomAttributeType;
use RS\DiExtraBundle\Annotation\ClassProcessorInterface;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\MethodProcessorInterface;
use RS\DiExtraBundle\Converter\Annotation\ObserveMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

class MethodParser
{
    /** @var \ReflectionMethod  */
    protected $reflectionMethod;
    /** @var Reader  */
    protected $annotationReader;

    public function __construct(Reader $annotationReader, \ReflectionMethod $reflectionMethod)
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
        foreach ($this->getMethodAnnotation() as $annotation) {
            $annotation->handleMethod($classMeta, $this->reflectionMethod);
        }
    }

    protected function getMethodAnnotation(): iterable
    {
        foreach ($this->annotationReader->getMethodAnnotations($this->reflectionMethod) as $annotation) {
            if($annotation instanceof MethodProcessorInterface){
                yield $annotation;
            }
        }
        foreach ($this->reflectionMethod->getAttributes() as $attribute) {
            $annotation = $attribute->newInstance();
            if($annotation instanceof MethodProcessorInterface){
                if($annotation instanceof InjectParams){
                    $this->parseInjectParams($annotation);
                }
                yield $annotation;
            }
        }
    }

    protected function parseInjectParams(InjectParams $injectParams)
    {
        foreach ($this->reflectionMethod->getAttributes() as $attribute) {
            $annotation = $attribute->newInstance();
            if($annotation instanceof Inject) {
                $injectParams->params[$annotation->getName()] = $annotation;
            }
        }
    }
}
