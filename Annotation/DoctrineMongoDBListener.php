<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Converter\Annotation\DoctrineMongoDBListenerClassHandler;

/**
 * @Annotation
 * @Target("CLASS")
 */
class DoctrineMongoDBListener implements ClassProcessorInterface
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
        (new DoctrineMongoDBListenerClassHandler())->handle($classMeta, $reflectionClass, $this);
    }

}
