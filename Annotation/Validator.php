<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\Annotation\ValidatorClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Validator implements ClassProcessorInterface
{
    /** @var string */
    public $alias;

    public function __construct($alias = null)
    {
        if(is_array($alias)) {
            $this->alias = $alias['alias']??$alias['value']??null;
            return;
        }
        $this->alias = $alias;
    }

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new ValidatorClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
