<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class TagMethodHandler
{
    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionClass $reflector
     * @param Tag $annotation
     */
    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflector, Tag $annotation)
    {
        $factoryMethodName = "{$reflector->getDeclaringClass()->getName()}:{$reflector->getName()}";

        if(!isset($classMeta->factoryTags[$factoryMethodName])){
            $classMeta->factoryTags[$factoryMethodName] = array();
        }

        if(!isset($classMeta->factoryTags[$factoryMethodName][$annotation->name])){
            $classMeta->factoryTags[$factoryMethodName][$annotation->name] = array();
        }

        $classMeta->factoryTags[$factoryMethodName][$annotation->name][] = $annotation->attributes;
    }
}
