<?php

namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service()
 */
class FooTraitInjectService
{
    use Traits\InjectFooTrait;
}