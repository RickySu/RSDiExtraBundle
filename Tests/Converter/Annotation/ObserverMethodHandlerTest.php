<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Observe;
use RS\DiExtraBundle\Converter\Annotation\ObserveMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ObserverMethodHandlerTest extends BaseTestCase
{
    public function test_handle()
    {
        //arrange
        $classMeta = new ClassMeta();
        $observer = new Observe();
        $observer->event = 'kernel.request';
        $observer->priority = 20;
        $observerMethodHandler = new ObserveMethodHandler();

        //act
        $observerMethodHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle'), $observer);

        //assert
        $this->assertEquals(array(
            'kernel.event_listener' => array(
                array(
                    'event' => $observer->event,
                    'priority' => $observer->priority,
                    'method' => 'test_handle',
                ),
            ),
        ), $classMeta->tags);
    }
}