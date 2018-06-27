<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\FormTypeClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class FormType implements ClassProcessorInterface
{
    /** @var string */
    public $alias;

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new FormTypeClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
