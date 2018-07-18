<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Data;

use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Validator as Assert;

class Bar
{
    /**
     * @Assert\Foo()
     */
    public $foo;
}