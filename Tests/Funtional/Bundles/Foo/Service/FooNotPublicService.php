<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;

/**
 * @Service("foo_not_public", public=false)
 * @Tag("bar_tag")
 */
class FooNotPublicService
{
    public $params = array('id' => 'foo_not_public',);
}