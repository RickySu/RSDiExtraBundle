<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\DoctrineListener;
use RS\DiExtraBundle\Annotation\DoctrineMongoDBListener;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class DoctrineMongoDBListenerClassHandler
{
    const EVENT_LISTENER_TAG = 'doctrine_mongodb.odm.event_listener';

    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, DoctrineMongoDBListener $doctrineMongoDBListenerAnnotation)
    {
        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->public = false;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        foreach ($doctrineMongoDBListenerAnnotation->events as $event) {
            $tagAnnotation = new Tag();
            $tagAnnotation->name = self::EVENT_LISTENER_TAG;
            $tagAnnotation->attributes = array(
                'event' => $event,
                'connection' => $doctrineMongoDBListenerAnnotation->connection,
                'lazy' => $doctrineMongoDBListenerAnnotation->lazy,
                'priority' => $doctrineMongoDBListenerAnnotation->priority,
            );
            (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);
        }
    }
}