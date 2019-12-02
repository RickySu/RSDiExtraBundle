<?php

namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Controller;

use RS\DiExtraBundle\Annotation\Inject;

trait FooTrait
{
    /**
     * @Inject("%foo%")
     */
    protected $fooFromTrait;
}
