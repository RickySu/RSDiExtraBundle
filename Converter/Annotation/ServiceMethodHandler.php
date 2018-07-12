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
        $factoryClassMeta->parent = $annotation->parent;
        $factoryClassMeta->shared = $annotation->shared;
        $factoryClassMeta->public = $annotation->public;
        $factoryClassMeta->private = $annotation->private;
        $factoryClassMeta->decorates = $annotation->decorates;
        $factoryClassMeta->decorationInnerName = $annotation->decorationInnerName;
        $factoryClassMeta->decorationPriority = $annotation->decorationPriority;
        $factoryClassMeta->deprecated = $annotation->deprecated;
        $factoryClassMeta->abstract = $annotation->abstract;
        $factoryClassMeta->environments = $annotation->environments;
        $factoryClassMeta->autowire = $annotation->autowire;
        $factoryClassMeta->synthetic = $annotation->synthetic;
        $factoryClassMeta->lazy = $annotation->lazy;
        $factoryClassMeta->autoconfigured = $annotation->autoconfigured;

        $factoryClassMeta->class = $reflectionMethod->getDeclaringClass()->getName();

        if($factoryClassMeta->id == null){
            $factoryClassMeta->id = "{$classMeta->class}.{$reflectionMethod->getName()}";
        }

        if($classMeta->id) {
            $factoryClassMeta->factoryMethod = array(
                new Reference($classMeta->id),
                $reflectionMethod->getName()
            );
            return;
        }


        $factoryClassMeta->factoryMethod = array(
            $reflectionMethod->getDeclaringClass()->getName(),
            $reflectionMethod->getName()
        );

        if ($classMeta->methodCalls) {
            foreach ($classMeta->methodCalls as $methodCall){
                list($methodName, $arguments) = $methodCall;
                if($methodName == $reflectionMethod->getName()){
                    $factoryClassMeta->arguments = $arguments;
                }
            }
            $classMeta->methodCalls = array();
        }

    }

    protected function isFactoryClass(ClassMeta $classMeta)
    {
        return $classMeta->id == null;
    }
}
