<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Form;

use RS\DiExtraBundle\Annotation\FormType;
use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooPublicService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @FormType
 */
class CustomType extends AbstractType implements DataTransformerInterface
{
    /** @var FooPublicService  */
    protected $fooPublicService;

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%")
     * })
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addViewTransformer($this)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'custom';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($value)
    {
        return $this->foo;
    }
}