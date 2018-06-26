<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\TagClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Tag implements ClassProcessorInterface
{
    /** @var string @Required */
    public $name;

    /** @var array */
    public $attributes = array();

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
