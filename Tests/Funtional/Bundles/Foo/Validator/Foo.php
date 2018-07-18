<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Foo extends Constraint
{
    public $message = 'This value should must be %value%';
}
