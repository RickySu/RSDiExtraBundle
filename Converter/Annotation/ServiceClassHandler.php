<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Converter\ClassMeta;

class ServiceClassHandler
{

    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionClass $reflector
     * @param $annotation
     */
    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, Service $annotation)
    {
        $classMeta->id = $annotation->id;
        $classMeta->parent = $annotation->parent;
        $classMeta->shared = $annotation->shared;
        $classMeta->public = $annotation->public;
        $classMeta->private = $annotation->private;
        $classMeta->decorates = $annotation->decorates;
        $classMeta->decorationInnerName = $annotation->decorationInnerName;
        $classMeta->decorationPriority = $annotation->decorationPriority;
        $classMeta->deprecated = $annotation->deprecated;
        $classMeta->abstract = $annotation->abstract;
        $classMeta->environments = $annotation->environments;
        $classMeta->autowire = $annotation->autowire;
        $classMeta->synthetic = $annotation->synthetic;
        $classMeta->lazy = $annotation->lazy;
        $classMeta->autoconfigured = $annotation->autoconfigured;

        if($classMeta->id == null){
            $classMeta->id = $classMeta->class;
        }
    }
}
