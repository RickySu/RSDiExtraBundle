<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\ServiceClassHandler;
use RS\DiExtraBundle\Converter\Annotation\ServiceMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
final class Service implements ClassProcessorInterface, MethodProcessorInterface
{
    /** @var string */
    public $id;

    /** @var string */
    public $parent;

    /** @var bool */
    public $public = true;

    /** @var bool */
    public $private = false;

    /** @var bool */
    public $shared;

    /** @var string */
    public $deprecated;

    /** @var string */
    public $decorates;

    /** @var string */
    public $decorationInnerName;

    /** @var integer */
    public $decorationPriority;

    /** @var bool */
    public $abstract;

    /** @var array<string> */
    public $environments = array();

    /** @var bool */
    public $autowire;

    /** @var bool */
    public $synthetic;

    /** @var bool */
    public $lazy;

    /** @var bool */
    public $autoconfigured;
    
    /** @var string */
    public $class;

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $this);
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new ServiceMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
