<?php
namespace RS\DiExtraBundle\Tests\Funtional;

use RS\DiExtraBundle\Tests\BaseWebTestCase;

class ControllerTest extends BaseWebTestCase
{
    public function test_property_inject()
    {
        //arrange

        //act
        $this->client->request('GET', "/foo/bar");
        $result = json_decode($this->client->getResponse()->getContent(), true);

        //assert
        $this->assertEquals('foo_static_factory', $result['fooStaticFactory']['params']['id']);
        $this->assertEquals('foo_static_factory2', $result['fooStaticFactory2']['params']['id']);
        $this->assertEquals('foo_static_factory3', $result['injectParams']['fooStaticFactory3']['params']['id']);
        $this->assertEquals('bar', $result['injectParams']['foo']);
        $this->assertEquals('foo_static_factory', $result['injectParams1']['fooStaticFactory']['params']['id']);
        $this->assertEquals('foo_static_factory2', $result['injectParams1']['fooStaticFactory2']['params']['id']);
        $this->assertEquals('foo_static_factory3', $result['injectParams1']['fooStaticFactory3']['params']['id']);
        $this->assertEquals('bar', $result['injectParams1']['foo']);
        $this->assertEquals('foo_static_factory2', $result['constructParams']['fooStaticFactory2']['params']['id']);
        $this->assertEquals('foo_static_factory3', $result['constructParams']['fooStaticFactory3']['params']['id']);
        $this->assertEquals('bar', $result['constructParams']['foo']);

    }
}