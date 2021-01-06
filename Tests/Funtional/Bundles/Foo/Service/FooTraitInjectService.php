<?php

namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service()
 */
class FooTraitInjectService
{
    use Traits\InjectFooTrait;
}