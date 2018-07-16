<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;


use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;

class StaticFactory
{
    /**
     * @Service("foo_static_factory", class=\stdClass::class)
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
     * @Service("foo_static_factory2", class=\stdClass::class)
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
