<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\DoctrineListener;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class DoctrineListenerClassHandler
{
    const EVENT_LISTENER_TAG = 'doctrine.event_listener';

    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, DoctrineListener $doctrineListenerAnnotation)
    {
        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->public = false;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        foreach ($doctrineListenerAnnotation->events as $event) {
            $tagAnnotation = new Tag();
            $tagAnnotation->name = self::EVENT_LISTENER_TAG;
            $tagAnnotation->attributes = array(
                'event' => $event,
                'connection' => $doctrineListenerAnnotation->connection,
                'lazy' => $doctrineListenerAnnotation->lazy,
                'priority' => $doctrineListenerAnnotation->priority,
            );
            (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);
        }
    }
}