<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class ParameterGuesser
{
    public function guessArgument($name, \ReflectionType $reflectionType = null)
    {
        if($reflectionType){
            return new Reference(
                $reflectionType->getName(),
                ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
            );
        }

        return new Reference(
            $this->camelToSnake($name),
            ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
        );
    }

    public function guessAnnotationArgument($annotationValue, $required = false)
    {
        if($this->isTagged($annotationValue)){
            list(, $value) = explode(' ', $annotationValue);
            return new TaggedIteratorArgument($value);
        }

        if($this->isParameters($annotationValue)){
            return $annotationValue;
        }

        return new Reference(
            $annotationValue,
            $required?ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE:ContainerInterface::IGNORE_ON_INVALID_REFERENCE
        );
    }

    protected function isParameters($name)
    {
        return (bool) preg_match('/^%.*?%$/', $name, $match);
    }

    protected function isTagged($name)
    {
        return strpos($name, '!tagged ') === 0;
    }

    protected function camelToSnake($camel)
    {
        $snake = preg_replace_callback('/[A-Z]/', function ($match){
            return '_' . strtolower($match[0]);
        }, $camel);
        return ltrim($snake, '_');
    }

}