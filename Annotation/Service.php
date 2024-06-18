<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\ServiceClassHandler;
use RS\DiExtraBundle\Converter\Annotation\ServiceMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class Service implements ClassProcessorInterface, MethodProcessorInterface
{
    /** @var string */
    public $id;

    /** @var string */
    public $parent;

    /** @var bool */
    public $public = true;

    /** @var bool */
    public $shared = true;

    /** @var null | array */
    public $deprecated = null;

    /** @var string */
    public $decorates;

    /** @var string */
    public $decorationInnerName;

    /** @var integer */
    public $decorationPriority = 0;

    /** @var bool */
    public $abstract = false;

    /** @var array<string> */
    public $environments = array();

    /** @var bool */
    public $autowire = false;

    /** @var bool */
    public $synthetic = false;

    /** @var bool */
    public $lazy = false;

    /** @var bool */
    public $autoconfigured = false;

    /** @var string */
    public $class;

    public function __construct(
        $id = null,
        $parent = null,
        $public = true,
        $shared = true,
        $deprecated = null,
        $decorates = null,
        $decorationInnerName = null,
        $decorationPriority = 0,
        $abstract = false,
        $environments = array(),
        $autowire = false,
        $synthetic = false,
        $lazy = false,
        $autoconfigured = false,
        $class = null
    )
    {
        if(is_array($id) && isset($id['value'])) {
            $this->id = $id['value']??null;
            $this->parent = $id['parent']??null;
            $this->public = $id['public']??true;
            $this->shared = $id['shared']??true;
            $this->deprecated = $id['deprecated']??null;
            $this->decorates = $id['decorates']??null;
            $this->decorationInnerName = $id['decorationInnerName']??null;
            $this->decorationPriority = $id['decorationPriority']??0;
            $this->abstract = $id['abstract']??false;
            $this->environments = $id['environments']??array();
            $this->autowire = $id['autowire']??false;
            $this->synthetic = $id['synthetic']??false;
            $this->lazy = $id['lazy']??false;
            $this->autoconfigured = $id['autoconfigured']??false;
            $this->class = $id['class']??null;
            return;
        }
        $this->id = $id;
        $this->parent = $parent;
        $this->public = $public;
        $this->shared = $shared;
        $this->deprecated = $deprecated;
        $this->decorates = $decorates;
        $this->decorationInnerName = $decorationInnerName;
        $this->decorationPriority = $decorationPriority;
        $this->abstract = $abstract;
        $this->environments = $environments;
        $this->autowire = $autowire;
        $this->synthetic = $synthetic;
        $this->lazy = $lazy;
        $this->autoconfigured = $autoconfigured;
        $this->class = $class;
    }

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $this);
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new ServiceMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
