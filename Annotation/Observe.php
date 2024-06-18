<?php
namespace RS\DiExtraBundle\Annotation;
use RS\DiExtraBundle\Converter\Annotation\ObserveMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class Observe implements MethodProcessorInterface
{
    /** @var string @Required */
    public $event;

    /** @var int */
    public $priority = 0;

    public function __construct($event = null, $priority = 0)
    {
        if(is_array($event)) {
            $this->event = $event['event']??$event['value']??null;
            $this->priority = $event['priority']??false;
            return;
        }
        $this->event = $event;
        $this->priority = $priority;
    }

    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        (new ObserveMethodHandler())->handle($classMeta, $reflectionMethod, $this);
    }
}
