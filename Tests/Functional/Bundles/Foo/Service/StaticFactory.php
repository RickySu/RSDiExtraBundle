<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;


use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;

class StaticFactory
{
    /**
     * @Tag("tag_index_by", attributes={"index": "foo_static"})
     * @Tag(name="foo_tag")
     * @Tag(name="foo_tag", attributes={"a": "a"})
     * @Service("foo_static_factory", class=\stdClass::class)
     * @Tag(name="bar_tag")
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public static function create(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $object = new \stdClass();
        $object->params = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
            'id' => 'foo_static_factory',
        );
        return $object;
    }

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     *
     * @Tag("tag_index_by", attributes={"index": "buz"})
     * @Tag(name="foo_tag")
     * @Service("foo_static_factory2", class=\stdClass::class)
     * @Tag(name="bar_tag")
     */
    public static function create2(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $object = new \stdClass();
        $object->params = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
            'id' => 'foo_static_factory2',
        );
        return $object;
    }

    /**
     * @InjectParams({
     *     "fooNotPublicService" = @Inject("foo_not_public"),
     *     "foo" = @Inject("%foo%"),
     * })
     * @Service("foo_static_factory3", class=\stdClass::class)
     * @Tag(name="foo_tag")
     */
    public static function create3($foo, $fooNotPublic, FooNotPublicService $fooNotPublicService)
    {
        $object = new \stdClass();
        $object->params = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
            'id' => 'foo_static_factory3',
        );
        return $object;
    }
}
