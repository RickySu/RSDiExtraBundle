<?php
namespace RS\DiExtraBundle\Converter\Annotation;


use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Annotation\Validator;
use RS\DiExtraBundle\Converter\ClassMeta;

class ValidatorClassHandler
{
    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, Validator $validatorAnnotation)
    {
        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->public = false;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        $tagAnnotation = new Tag();
        $tagAnnotation->name = 'validator.constraint_validator';
        $tagAnnotation->attributes = array(
            'alias' => $validatorAnnotation->alias,
        );
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);
    }
}