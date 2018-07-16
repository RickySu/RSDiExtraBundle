<?php
namespace RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Service;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Tests\Funtional\CustomEvent;

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

    public $fooTagServices = array();

    public $barTagServices = array();
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

    /**
     * @InjectParams({
     *     "foo" = @Inject("!tagged foo_tag"),
     * })
     */
    public function injectFooTag(iterable $foo)
    {
        $this->fooTagServices = iterator_to_array($foo);
    }

    /**
     * @InjectParams({
     *     "bar" = @Inject("!tagged bar_tag"),
     * })
     */
    public function injectBarTag(iterable $bar)
    {
        $this->barTagServices = iterator_to_array($bar);
    }

    /**
     * @param CustomEvent $event
     * @Observe("custom_event", priority=10)
     */
    public function customEventListener1(CustomEvent $event)
    {
        $event->addCalls('customEventListener1');
    }

    /**
     * @param CustomEvent $event
     * @Observe("custom_event", priority=100)
     */
    public function customEventListener2(CustomEvent $event)
    {
        $event->addCalls('customEventListener2');
    }

    /**
     * @param CustomEvent $event
     * @Observe("custom_event")
     */
    public function customEventListener3(CustomEvent $event)
    {
        $event->addCalls('customEventListener3');
    }

    /**
     * @param CustomEvent $event
     * @Observe("custom_event", priority=5)
     */
    public function customEventListener4(CustomEvent $event)
    {
        $event->addCalls('customEventListener4');
    }

}
