<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\FormType;
use RS\DiExtraBundle\Converter\Annotation\FormTypeClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class FormTypeClassHandlerTest extends BaseTestCase
{
    public function test_handle_no_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $formType = new FormType();
        $formType->alias = 'foo';

        $formTypeHandler = new FormTypeClassHandler();

        //act
        $formTypeHandler->handle($classMeta, new \ReflectionClass($this), $formType);

        //assert
        $this->assertFalse($classMeta->public);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'form.type' => array(
                array('alias' => 'foo'),
            ),
        ), $classMeta->tags);
    }

    public function test_handle_has_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        $formType = new FormType();
        $formType->alias = 'foo';

        $formTypeHandler = new FormTypeClassHandler();

        //act
        $formTypeHandler->handle($classMeta, new \ReflectionClass($this), $formType);

        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'form.type' => array(
                array('alias' => 'foo'),
            ),
        ), $classMeta->tags);
    }

}
