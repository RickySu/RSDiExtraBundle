<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\CommandClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Command implements ClassProcessorInterface
{
    /** @var string */
    public $command;

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new CommandClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
