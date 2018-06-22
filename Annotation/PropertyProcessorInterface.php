<?php
namespace RS\DiExtraBundle\Annotation;

use RS\DiExtraBundle\Converter\ClassMeta;

interface PropertyProcessorInterface
{
    public function handleProperty(ClassMeta $classMeta, \ReflectionProperty $reflectionProperty);
}
