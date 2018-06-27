<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\Annotation\ValidatorClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Validator implements ClassProcessorInterface
{
    /** @var string @Required */
    public $alias;

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new ValidatorClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
