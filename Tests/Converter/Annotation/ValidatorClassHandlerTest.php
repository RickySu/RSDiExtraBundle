<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;


use RS\DiExtraBundle\Annotation\Validator;
use RS\DiExtraBundle\Converter\Annotation\ValidatorClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ValidatorClassHandlerTest extends BaseTestCase
{
    public function test_handle_no_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $validator = new Validator();
        $validator->alias = 'foo';

        $validatorHandler = new ValidatorClassHandler();

        //act
        $validatorHandler->handle($classMeta, new \ReflectionClass($this), $validator);

        //assert
        $this->assertFalse($classMeta->public);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'validator.constraint_validator' => array(
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

        $validator = new Validator();
        $validator->alias = 'foo';

        $validatorHandler = new ValidatorClassHandler();

        //act
        $validatorHandler->handle($classMeta, new \ReflectionClass($this), $validator);

        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'validator.constraint_validator' => array(
                array('alias' => 'foo'),
            ),
        ), $classMeta->tags);
    }

}
