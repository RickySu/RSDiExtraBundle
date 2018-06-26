<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Converter\ClassMeta;

class ObserveMethodHandler
{
    const EVENT_LISTENER_TAG = 'kernel.event_listener';

    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionMethod $reflectionMethod
     * @param Observe $annotation
     */
    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod, Observe $annotation)
    {
        if(!isset($classMeta->tags[self::EVENT_LISTENER_TAG])){
            $classMeta->tags[self::EVENT_LISTENER_TAG] = array();
        }

        $classMeta->tags[self::EVENT_LISTENER_TAG][] = array(
            'event' => $annotation->event,
            'priority' => $annotation->priority,
            'method' => $reflectionMethod->getName(),
        );
    }
}
