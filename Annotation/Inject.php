<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Annotation\InjectPropertyHandler;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
final class Inject implements PropertyProcessorInterface
{
    /** @var string */
    public $value;

    /** @var bool */
    public $required;

    public function handleProperty(ClassMeta $classMeta, \ReflectionProperty $reflectionProperty)
    {
        (new InjectPropertyHandler())->handle($classMeta, $reflectionProperty, $this);
    }
}
