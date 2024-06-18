<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\DoctrineListenerClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
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

    public function __construct($events = null, $connection = 'default', $lazy = true, $priority = 0)
    {
        if(is_array($events)){
            $this->events = $events['events']??$events['value']??null;
            $this->connection = $connection;
            $this->lazy = $lazy;
            $this->priority = $priority;
            return;
        }
        $this->events = $events;
        $this->connection = $connection;
        $this->lazy = $lazy;
        $this->priority = $priority;
    }

    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass)
    {
        (new DoctrineListenerClassHandler())->handle($classMeta, $reflectionClass, $this);
    }
}
