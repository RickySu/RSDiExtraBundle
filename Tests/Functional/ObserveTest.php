<?php
namespace RS\DiExtraBundle\Tests\Functional;

use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use Symfony\Contracts\EventDispatcher\Event;

class CustomEvent extends Event
{
    protected $calls = array();

    public function addCalls($call)
    {
        $this->calls[] = $call;
    }

    /**
     * @return array
     */
    public function getCalls(): array
    {
        return $this->calls;
    }
}

class ObserveTest extends BaseKernelTestCase
{
    public function test_event_listener_null_event()
    {
        //arrange
        $event = new CustomEvent();
        $eventDispatcher = self::getContainer()->get('event_dispatcher');

        //act
        $eventDispatcher->dispatch($event, 'null_event');

        //assert
        $this->assertEquals(array(), $event->getCalls());
    }

    public function test_event_listener_custom_event()
    {
        //arrange
        $event = new CustomEvent();
        $eventDispatcher = self::getContainer()->get('event_dispatcher');

        //act
        $eventDispatcher->dispatch($event, 'custom_event');

        //assert
        $this->assertEquals(array(
            'customEventListener2',
            'customEventListener1',
            'customEventListener4',
            'customEventListener3'
        ), $event->getCalls());
    }
}