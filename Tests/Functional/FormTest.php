<?php
namespace RS\DiExtraBundle\Tests\Functional;

use Functional\Bundles\Foo\Form\CustomAttributeType;
use Functional\Bundles\Foo\Service\HoldService;
use RS\DiExtraBundle\Tests\BaseKernelTestCase;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Data\Bar;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Form\CustomType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class FormTest extends BaseKernelTestCase
{
    public function test_createForm()
    {
        //arrange
        $data = new \stdClass();
        $data->foo = 'null';
        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $data)
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

        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $bar)
            ->add('foo', CustomType::class)
            ->getForm();

        $form->submit(array('foo' => 'foo'));

        //act
        //assert
        $this->assertFalse($form->isValid());
        $this->assertEquals('ERROR: This value should must be bar', trim($form->getErrors(true)));
    }

    /**
     * @group debug
     */
    public function test_createAttributeForm()
    {
        //arrange
        $data = new \stdClass();
        $data->foo = 'null';
        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $data)
            ->add('foo', CustomAttributeType::class)
            ->getForm();

        $form->submit(array('foo' => 'foo'));

        //act
        //assert
        $this->assertTrue($form->isValid());
        $this->assertEquals('bar', $data->foo);
    }

    public function test_createAttributeFormWithValidator()
    {
        //arrange
        $bar = new Bar();
        $bar->foo = 'null';

        $form = $this->container->get('form.factory')->createBuilder(FormType::class, $bar)
            ->add('foo', CustomAttributeType::class)
            ->getForm();

        $form->submit(array('foo' => 'foo'));

        //act
        //assert
        $this->assertFalse($form->isValid());
        $this->assertEquals('ERROR: This value should must be bar', trim($form->getErrors(true)));
    }
}

