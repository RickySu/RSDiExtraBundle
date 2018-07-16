<?php
namespace RS\DiExtraBundle\Converter\Annotation;


use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Converter\ClassMeta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class InjectParamsMethodHandler
{
    /** @var ParameterGuesser  */
    protected $parameterGuesser;

    public function __construct()
    {
        $this->parameterGuesser = new ParameterGuesser();
    }

    public function handle(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod, InjectParams $annotation)
    {
        $arguments = $this->convertArguments($reflectionMethod);
        $annotationArguments = $this->convertAnnotationArguments($annotation);
        $mappedArguments = $this->mapArguments($reflectionMethod, array_merge($arguments, $annotationArguments));
        $this->handleMethodInject($classMeta, $reflectionMethod, $mappedArguments);
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
            $arguments[$parameters[$i]->getName()] = $this->parameterGuesser->guessArgument($parameters[$i]->getName(), $parameters[$i]->getType());
        }

        return $arguments;
    }

    protected function convertAnnotationArguments(InjectParams $annotation)
    {
        $annotationArguments = array();

        foreach ($annotation->params as $paramName => $injectOption){
            $annotationArguments[$paramName] = $this->parameterGuesser->guessAnnotationArgument($injectOption->value, $injectOption->required);
        }

        return $annotationArguments;
    }

    /**
     * @param ClassMeta $classMeta
     * @param \ReflectionMethod $reflectionMethod
     * @param $mappedArguments
     */
    protected function handleMethodInject(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod, $mappedArguments): void
    {
        if ($reflectionMethod->isConstructor()) {
            $classMeta->arguments = $mappedArguments;
            return;
        }

        $classMeta->methodCalls[] = array($reflectionMethod->getName(), $mappedArguments);
    }

}
