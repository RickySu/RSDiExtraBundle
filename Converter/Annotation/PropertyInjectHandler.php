<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Converter\ClassMeta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PropertyInjectHandler
{
    /** @var ParameterGuesser  */
    protected $parameterGuesser;

    public function __construct()
    {
        $this->parameterGuesser = new ParameterGuesser();
    }

    public function handle(ClassMeta $classMeta, \ReflectionProperty $reflectionProperty, Inject $annotation)
    {
        if($classMeta->class == null){
            $classMeta->class = $reflectionProperty->getDeclaringClass()->getName();
        }

        if($classMeta->id == null){
            $classMeta->id = $classMeta->class;
        }

        if($reflectionProperty->getDeclaringClass()->isSubclassOf(Controller::class)){
            if($annotation->value === null){
                $classMeta->controllerProperties[$reflectionProperty->getName()] = $this->parameterGuesser->guessArgument($reflectionProperty->getName());
                return;
            }

            $classMeta->controllerProperties[$reflectionProperty->getName()] = $this->parameterGuesser->guessAnnotationArgument($annotation->value, $annotation->required);
            return;
        }

        if($annotation->value === null){
            $classMeta->properties[$reflectionProperty->getName()] = $this->parameterGuesser->guessArgument($reflectionProperty->getName());
            return;
        }

        $classMeta->properties[$reflectionProperty->getName()] = $this->parameterGuesser->guessAnnotationArgument($annotation->value, $annotation->required);

    }
}
