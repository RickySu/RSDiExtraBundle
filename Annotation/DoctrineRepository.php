<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\DoctrineListenerClassHandler;
use RS\DiExtraBundle\Converter\Annotation\DoctrineRepositoryClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class DoctrineRepository implements ClassProcessorInterface
{
    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new DoctrineRepositoryClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
