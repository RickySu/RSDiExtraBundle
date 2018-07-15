<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\TagClassHandler;
use RS\DiExtraBundle\Converter\Annotation\TagMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS", "METHOD")
 */
final class Tag implements ClassProcessorInterface, MethodProcessorInterface
{
    /** @var string @Required */
    public $name;

    /** @var array */
    public $attributes = array();

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $this);
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new TagMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
