<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\DoctrineListenerClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
class DoctrineListener implements ClassProcessorInterface
{
    /** @var array<string> @Required */
    public $events;

    /** @var string */
    public $connection = 'default';

    /** @var bool */
    public $lazy = true;

    /** @var int */
    public $priority = 0;

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new DoctrineListenerClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
