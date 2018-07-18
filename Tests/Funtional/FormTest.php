<?php
namespace RS\DiExtraBundle\Tests\Funtional;

use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Data\Bar;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Foo\Form\CustomType;
use RS\DiExtraBundle\Tests\Funtional\Bundles\Validator\Foo;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class FormTest extends BaseKernelTestCase
{
    public function test_createForm()
    {
        //arrange
        $data = new \stdClass();
        $data->foo = 'null';

        $form = self::$container->get('form.factory')->createBuilder(FormType::class, $data)
            ->add('foo', CustomType::class)
            ->getForm();

        $form->submit(array('foo' => 'foo'));

        //act
        //assert
        $this->assertTrue($form->isValid());
        $this->assertEquals('bar', $data->foo);
    }

    public function test_createFormWithValidator()
    {
        //arrange
        $bar = new Bar();
        $bar->foo = 'null';

        $form = self::$container->get('form.factory')->createBuilder(FormType::class, $bar)
            ->add('foo', CustomType::class)
            ->getForm();

        $form->submit(array('foo' => 'foo'));

        //act
        //assert
        $this->assertFalse($form->isValid());
        $this->assertEquals('ERROR: This value should must be bar', trim($form->getErrors(true)));
    }

}

