<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\ClassMeta;

interface ClassProcessorInterface
{
    public function handleClass(ClassMeta $classMeta, \ReflectionClass $reflectionClass);
}