<?php

namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\Traits;

trait InjectFooTrait
{
    use InjectFooStaticFactoryTrait;
    use InjectFooStaticFactory2Trait;
}
