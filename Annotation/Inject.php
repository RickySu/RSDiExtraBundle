<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Annotation\InjectPropertyHandler;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class Inject implements PropertyProcessorInterface
{
    /** @var string */
    public $value;

    /** @var bool */
    public $required;

    protected $name;

    public function __construct($name = null, $value = null, $required = false)
    {
        if(is_array($name)) {
            if(isset($name['value'])) {
                $this->value = $name['value'];
                $this->required = $name['required'] ?? false;
            }
            return;
        }

        if($value === null) {
            $this->value = $name;
            return;
        }

        $this->name = $name;
        $this->value = $value;
        $this->required = $required;
    }

    public function handleProperty(ClassMeta $classMeta, \ReflectionProperty $reflectionProperty)
    {
        (new InjectPropertyHandler())->handle($classMeta, $reflectionProperty, $this);
    }

    public function getName()
    {
        return $this->name;
    }
}
