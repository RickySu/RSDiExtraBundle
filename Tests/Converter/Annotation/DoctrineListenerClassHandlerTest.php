<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\DoctrineListener;
use RS\DiExtraBundle\Converter\Annotation\DoctrineListenerClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class DoctrineListenerClassHandlerTest extends BaseTestCase
{
    public function test_handle_no_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $doctrineListener = new DoctrineListener();
        $doctrineListener->events = array('preUpdate', 'prePersist');
        $doctrineListener->connection = 'default';
        $doctrineListener->lazy = true;
        $doctrineListener->priority = 20;
        $doctrineListenerHandler = new DoctrineListenerClassHandler();

        //act
        $doctrineListenerHandler->handle($classMeta, new \ReflectionClass($this), $doctrineListener);

        //assert
        $this->assertFalse($classMeta->public);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'doctrine.event_listener' => array(
                array(
                    'event' => 'preUpdate',
                    'connection' => 'default',
                    'priority' => 20,
                    'lazy' => true,
                ),
                array(
                    'event' => 'prePersist',
                    'connection' => 'default',
                    'priority' => 20,
                    'lazy' => true,
                ),
            ),
        ), $classMeta->tags);
    }

    public function test_handle_has_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        $doctrineListener = new DoctrineListener();
        $doctrineListener->events = array('preUpdate', 'prePersist');
        $doctrineListener->connection = 'default';
        $doctrineListener->lazy = true;
        $doctrineListener->priority = 20;
        $doctrineListenerHandler = new DoctrineListenerClassHandler();

        //act
        $doctrineListenerHandler->handle($classMeta, new \ReflectionClass($this), $doctrineListener);


        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'doctrine.event_listener' => array(
                array(
                    'event' => 'preUpdate',
                    'connection' => 'default',
                    'priority' => 20,
                    'lazy' => true,
                ),
                array(
                    'event' => 'prePersist',
                    'connection' => 'default',
                    'priority' => 20,
                    'lazy' => true,
                ),
            ),
        ), $classMeta->tags);
    }

}
