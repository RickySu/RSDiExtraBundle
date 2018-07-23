<?php
namespace RS\DiExtraBundle\Converter\Annotation;

use RS\DiExtraBundle\Annotation\FormType;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\ClassMeta;

class FormTypeClassHandler
{
    public function handle(ClassMeta $classMeta, \ReflectionClass $reflectionClass, FormType $formTypeAnnotation)
    {
        if($classMeta->id === null) {
            $serviceAnnotation = new Service();
            $serviceAnnotation->public = false;
            (new ServiceClassHandler())->handle($classMeta, $reflectionClass, $serviceAnnotation);
        }

        $tagAnnotation = new Tag();
        $tagAnnotation->name = 'form.type';
        (new TagClassHandler())->handle($classMeta, $reflectionClass, $tagAnnotation);
    }
}
