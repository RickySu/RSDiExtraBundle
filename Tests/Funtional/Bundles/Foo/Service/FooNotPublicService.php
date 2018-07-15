<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;
use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service("foo_not_public", public=false)
 */
class FooNotPublicService
{
}