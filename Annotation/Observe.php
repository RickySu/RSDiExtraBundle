<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\ObserveMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Observe implements MethodProcessorInterface
{
    /** @var string @Required */
    public $event;

    /** @var int */
    public $priority = 0;

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new ObserveMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
