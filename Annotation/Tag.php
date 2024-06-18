<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\TagClassHandler;
use RS\DiExtraBundle\Converter\Annotation\TagMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class Tag implements ClassProcessorInterface, MethodProcessorInterface
{
    /** @var string @Required */
    public $name;

    /** @var array */
    public $attributes = array();

    public function __construct($name = null, $attributes = array())
    {
        if(is_array($name)) {
            $this->name = $name['name']??$name['value']??null;
            $this->attributes = $name['attributes'] ?? array();
            return;
        }
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $this);
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new TagMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
