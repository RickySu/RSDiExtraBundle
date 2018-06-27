<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;


use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\Annotation\TagClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class TagClassHandlerTest extends BaseTestCase
{
    public function test_handle_empty_array()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $tag = new Tag();
        $tag->name = 'foo';
        $tag->attributes = array('bar' => 'buz');

        $tagClassHandler = new TagClassHandler();

        //act
        $tagClassHandler->handle($classMeta, new \ReflectionClass($this), $tag);

        //assert
        $this->assertEquals(array(
            'foo' => array(
                array('bar' => 'buz')
            )
        ), $classMeta->tags);
    }

    public function test_handle_append_array()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $classMeta->tags['foo'] = array(
            array('bar1' => 'buz'),
        );
        $tag = new Tag();
        $tag->name = 'foo';
        $tag->attributes = array('bar2' => 'buz');

        $tagClassHandler = new TagClassHandler();

        //act
        $tagClassHandler->handle($classMeta, new \ReflectionClass($this), $tag);

        //assert
        $this->assertEquals(array(
            'foo' => array(
                array('bar1' => 'buz'),
                array('bar2' => 'buz'),
            )
        ), $classMeta->tags);
    }

}