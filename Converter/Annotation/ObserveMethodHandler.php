<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Annotation\Tag;
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
        $tagAnnotation = new Tag();
        $tagAnnotation->name = self::EVENT_LISTENER_TAG;
        $tagAnnotation->attributes = array(
            'event' => $annotation->event,
            'priority' => $annotation->priority,
            'method' => $reflectionMethod->getName(),
        );
        $tagAnnotation->handleClass($classMeta, $reflectionMethod->getDeclaringClass());
    }
}
