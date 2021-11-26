<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Annotation\Tag;

/**
 * @Service()
 * @notImportedAnnotations
 */
class ServiceFactory
{
    protected $params = array();

    /**
     * @var FooChildService
     */
    public $fooChild;

    /**
     * @InjectParams({
     *     "fooChildService" = @Inject("foo_child"),
     * })
     */
    public function injectFooChild(FooChildService $fooChildService)
    {
        $this->fooChild = $fooChildService;
    }

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%"),
     * })
     */
    public function __construct(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $this->params = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
        );
    }

    /**
     *
     * @Service("foo_service_factory", class=\stdClass::class)
     * @Tag("foo_tag", attributes={"foo": "foo"})
     * @Tag("tag_index_by", attributes={"index": "foo"})
     */
    public function create()
    {
        $object = new \stdClass();
        $object->params = $this->params;
        $object->params['id'] = 'foo_service_factory';
        return $object;
    }
}
