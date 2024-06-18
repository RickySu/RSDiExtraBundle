<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Validator;

use RS\DiExtraBundle\Annotation\Inject;
use RS\DiExtraBundle\Annotation\InjectParams;
use RS\DiExtraBundle\Annotation\Validator;
use RS\DiExtraBundle\Tests\Functional\Bundles\Foo\Service\FooPublicService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Validator
 */
class FooValidator extends ConstraintValidator
{
    protected $fooPublicService;
    protected $foo;

    /**
     * @InjectParams({
     *     "foo" = @Inject("%foo%", required=true)
     * })
     */
    public function inject($foo)
    {
        $this->foo = $foo;
    }

    public function validate($value, Constraint $constraint)
    {
        if($value != 'foo'){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%value%', $this->foo)
                ->addViolation();
        }
    }
}
