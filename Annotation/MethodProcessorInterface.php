<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\ClassMeta;

interface MethodProcessorInterface
{
    public function handleMethod(ClassMeta $classMeta, \ReflectionMethod $reflectionMethod);
}
