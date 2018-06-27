<?php
namespace RS\DiExtraBundle\Converter\Annotation;


use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Converter\ClassMeta;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class MethodInjectParamsHandler
{
    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod, InjectParams $annotation)
    {
        $arguments = $this->convertArguments($reflectionMethod);
        $annotationArguments = $this->convertAnnotationArguments($annotation);
        $mappedArguments = $this->mapArguments($reflectionMethod, array_merge($arguments, $annotationArguments));

        if($reflectionMethod->isConstructor()) {
            $classMeta->arguments = $mappedArguments;
            return;
        }

        if ($factoryClassMeta = $this->findFactoryClassMeta($classMeta, $reflectionMethod)){
            $factoryClassMeta->arguments = $mappedArguments;
            return;
        }

        $classMeta->methodCalls[] = array($reflectionMethod->getName(), $mappedArguments);
    }

    protected function findFactoryClassMeta(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod)
    {
        while($classMeta){
            if($classMeta->factoryMethod &&
                $classMeta->class == $reflectionMethod->getDeclaringClass()->getName() &&
                $classMeta->factoryMethod[1] == $reflectionMethod->getName()
            ){
                return $classMeta;
            }
            $classMeta = $classMeta->nextClassMeta;
        }
        return false;
    }

    protected function mapArguments(\ReflectionMethod $reflectionMethod, $arguments)
    {
        $result = array();
        foreach ($reflectionMethod->getParameters() as $parameter){
            if(isset($arguments[$parameter->getName()])) {
                $result[] = $arguments[$parameter->getName()];
            }
        }
        return $result;
    }

    protected function convertArguments(\ReflectionMethod $reflectionMethod)
    {
        $arguments = array();
        $parameters = $reflectionMethod->getParameters();
        for($i = 0; $i < $reflectionMethod->getNumberOfRequiredParameters(); $i++){
            $arguments[$parameters[$i]->getName()] = new Reference(
                $this->camelToSnake($parameters[$i]->getName()),
                ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
            );
        }

        return $arguments;
    }

    protected function convertAnnotationArguments(InjectParams $annotation)
    {
        $annotationArguments = array();

        foreach ($annotation->params as $paramName => $injectOption){
            if($this->isTagged($injectOption->value)){
                list(, $value) = explode(' ', $injectOption->value);
                $annotationArguments[$paramName] = new TaggedIteratorArgument($value);
                continue;
            }

            if($this->isParameters($injectOption->value)){
                $annotationArguments[$paramName] = $injectOption->value;
                continue;
            }

            $annotationArguments[$paramName] = new Reference(
                $injectOption->value,
                $injectOption->required?ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE:ContainerInterface::IGNORE_ON_INVALID_REFERENCE
            );
        }

        return $annotationArguments;
    }

    protected function camelToSnake($camel)
    {
        $snake = preg_replace_callback('/[A-Z]/', function ($match){
            return '_' . strtolower($match[0]);
        }, $camel);
        return ltrim($snake, '_');
    }

    protected function isParameters($name)
    {
        return (bool) preg_match('/^%.*?%$/', $name, $match);
    }

    protected function isTagged($name)
    {
        return strpos($name, '!tagged ') === 0;
    }

}
