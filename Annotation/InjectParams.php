<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\MethodInjectParamsHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class InjectParams implements MethodProcessorInterface
{
    /** @var array<RS\DiExtraBundle\Annotation\Inject> */
    public $params = array();

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new MethodInjectParamsHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
