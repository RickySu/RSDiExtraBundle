<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;

/**
 * @Service("foo_public")
 */
class FooPublicService
{
    /**
     * @var FooNotPublicService
     * @Inject("foo_not_public")
     */
    public $fooNotPublicWithId;

    /**
     * @var FooNotPublicService
     * @Inject()
     */
    public $fooNotPublic;

    public $constructParams = array();
    public $injectParams = array();

    /**
     * FooPublicService constructor.
     * @param FooPublicService $fooPublicService
     * @param $fooPublic
     * @param $foo
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public function __construct(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $this->constructParams = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
        );
    }


    /**
     * FooPublicService constructor.
     * @param FooPublicService $fooPublicService
     * @param $fooPublic
     * @param $foo
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public function inject(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $this->injectParams = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
        );
    }

}
