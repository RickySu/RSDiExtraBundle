<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\CommandClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class Command implements ClassProcessorInterface
{
    /** @var string */
    public $command;

    public function __construct($command = null)
    {
        if(is_array($command)) {
            $this->command = $command['command']??$command['value']??null;
            return;
        }
        $this->command = $command;
    }

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new CommandClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
