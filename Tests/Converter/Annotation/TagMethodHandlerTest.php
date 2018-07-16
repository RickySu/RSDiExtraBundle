<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;


use RS\DiExtraBundle\Annotation\Tag;
use RS\DiExtraBundle\Converter\Annotation\TagMethodHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class TagMethodHandlerTest extends BaseTestCase
{
    public function test_handle_empty_array()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $tag = new Tag();
        $tag->name = 'foo';
        $tag->attributes = array('bar' => 'buz');

        $tagMethodHandler = new TagMethodHandler();

        //act
        $tagMethodHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_empty_array'), $tag);

        //assert
        $this->assertEquals(array(
            self::class.':test_handle_empty_array' => array(
                'foo' => array(
                    array('bar' => 'buz')
                ),
            )
        ), $classMeta->factoryTags);
    }

    public function test_handle_append_array()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->factoryTags = array(
            self::class.':test_handle_empty_array' => array(
                'foo' => array(
                    array('bar' => 'buz')
                ),
            )
        );

        $tag = new Tag();
        $tag->name = 'foo';
        $tag->attributes = array('bar1' => 'buz1');

        $tagMethodHandler = new TagMethodHandler();

        //act
        $tagMethodHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_empty_array'), $tag);

        //assert
        $this->assertEquals(array(
            self::class.':test_handle_empty_array' => array(
                'foo' => array(
                    array('bar' => 'buz'),
                    array('bar1' => 'buz1')
                ),
            )
        ), $classMeta->factoryTags);
    }

    public function test_handle_append_array_with_other_key()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->factoryTags = array(
            self::class.':test_handle_empty_array' => array(
                'bar' => array(
                    array('bar' => 'buz')
                ),
            )
        );

        $tag = new Tag();
        $tag->name = 'foo';
        $tag->attributes = array('bar1' => 'buz1');

        $tagMethodHandler = new TagMethodHandler();

        //act
        $tagMethodHandler->handle($classMeta, new \ReflectionMethod($this, 'test_handle_empty_array'), $tag);

        //assert
        $this->assertEquals(array(
            self::class.':test_handle_empty_array' => array(
                'bar' => array(
                    array('bar' => 'buz'),
                ),
                'foo' => array(
                    array('bar1' => 'buz1'),
                ),
            )
        ), $classMeta->factoryTags);
    }

}