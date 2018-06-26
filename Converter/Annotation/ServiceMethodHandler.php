<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Converter\ClassMeta;
use Symfony\Component\DependencyInjection\Reference;

class ServiceMethodHandler
{
    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod, Service $annotation)
    {
        $factoryClassMeta = new ClassMeta();

        if($classMeta->nextClassMeta){
            $factoryClassMeta->nextClassMeta = $classMeta->nextClassMeta;
        }

        $classMeta->nextClassMeta = $factoryClassMeta;

        $factoryClassMeta->id = $annotation->id;

        $factoryClassMeta->public = $annotation->public;
        $factoryClassMeta->private = $annotation->private;
        $factoryClassMeta->shared = $annotation->shared;
        $factoryClassMeta->abstract = $annotation->abstract;
        $factoryClassMeta->environments = $annotation->environments;
        $factoryClassMeta->autowire = $annotation->autowire;

        if($classMeta->id) {
            $factoryClassMeta->factoryMethod = array(
                new Reference($classMeta->id),
                $reflectionMethod->getName()
            );
        }
        else{
            $factoryClassMeta->factoryMethod = array(
                $reflectionMethod->getDeclaringClass()->getName(),
                $reflectionMethod->getName()
            );
        }

        $factoryClassMeta->class = $reflectionMethod->getDeclaringClass()->getName();

        if($factoryClassMeta->id == null){
            $factoryClassMeta->id = "{$classMeta->class}.{$reflectionMethod->getName()}";
        }
    }
}
