<?php

namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service("foo_child", parent="foo_not_public", public=false)
 */
class FooChildService
{

}