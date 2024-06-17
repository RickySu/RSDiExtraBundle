<?php

namespace Functional\Bundles\Foo\Service;

use RS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @DI\Service()
 */
class HoldService
{
    /**
     * @var FormFactoryInterface
     * @DI\Inject("form.factory")
     */
    public FormFactoryInterface $formFactory;

    /**
     * @DI\Inject("entity_manager")
     */
    public $doctrine;
}
