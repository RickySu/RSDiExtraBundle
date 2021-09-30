<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Data;

use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Validator as Assert;

class Bar
{
    /**
     * @Assert\Foo()
     */
    public $foo;
}