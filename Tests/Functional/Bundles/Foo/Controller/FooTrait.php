<?php

namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Controller;

use RS\DiExtraBundle\Annotation\Inject;

trait FooTrait
{
    /**
     * @Inject("%foo%")
     */
    protected $fooFromTrait;
}
