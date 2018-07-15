<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class TagClassHandler
{

    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionClass $reflector
     * @param Tag $annotation
     */
    public function handle(ClassMeta $classMeta, \ReflectionClass $reflector, Tag $annotation)
    {
        if(!isset($classMeta->tags[$annotation->name])){
            $classMeta->tags[$annotation->name] = array();
        }

        $classMeta->tags[$annotation->name][] = $annotation->attributes;
    }

}
