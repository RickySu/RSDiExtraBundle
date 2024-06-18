<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Annotation\Service;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooNotPublicService;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooPublicService;
use RS\DiExtraBundle\Tests\Functional\CustomEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Service("foo_public_attribute")]
class FooPublicAttributeService
{
    #[Inject("foo_not_public")]
    public $fooNotPublicWithId;

    #[Inject()]
    public $fooNotPublic;

    public $constructParams = array();
    public $injectParams = array();

    public $fooTagServices = array();

    public $barTagServices = array();

    public $indexByTagServices = array();

    /**
     * FooPublicService constructor.
     * @param FooPublicService $fooPublicService
     * @param $fooPublic
     * @param $foo
     */
    #[InjectParams()]
    #[Inject(name: "foo", value: "%foo%")]
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
     */
    #[InjectParams()]
    #[Inject(name: "foo", value: "%foo%")]
    public function inject(FooNotPublicService $fooNotPublicService, $fooNotPublic, $foo)
    {
        $this->injectParams = array(
            'fooNotPublicService' => $fooNotPublicService,
            'fooNotPublic' => $fooNotPublic,
            'foo' => $foo,
        );
    }

    #[InjectParams()]
    #[Inject(name: "foo", value: "!tagged foo_tag")]
    public function injectFooTag(iterable $foo)
    {
        $this->fooTagServices = iterator_to_array($foo);
    }

    #[InjectParams()]
    #[Inject(name: "bar", value: "!tagged bar_tag")]
    public function injectBarTag(iterable $bar)
    {
        $this->barTagServices = iterator_to_array($bar);
    }

    #[InjectParams()]
    #[Inject(name: "foo", value: "!tagged tag_index_by index")]
    public function injectTagIndexBy(iterable $foo)
    {
        $this->indexByTagServices = iterator_to_array($foo);
    }

    #[Observe(event: "custom_event", priority: 10)]
    public function customEventListener1(CustomEvent $event)
    {
        $event->addCalls('customEventListener1');
    }

    #[Observe(event: "custom_event", priority: 100)]
    public function customEventListener2(CustomEvent $event)
    {
        $event->addCalls('customEventListener2');
    }

    #[Observe("custom_event")]
    public function customEventListener3(CustomEvent $event)
    {
        $event->addCalls('customEventListener3');
    }

    #[Observe(event: "custom_event", priority: 5)]
    public function customEventListener4(CustomEvent $event)
    {
        $event->addCalls('customEventListener4');
    }

}
