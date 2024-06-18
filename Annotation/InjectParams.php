<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\InjectParamsMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class InjectParams implements MethodProcessorInterface
{
    /** @var array<RS\DiExtraBundle\Annotation\Inject> */
    public $params = array();

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new InjectParamsMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
