<?php
namespace RS\DiExtraBundle\Tests\Functional\Bundles\Bar;

use RS\DiExtraBundle\Annotation\AutoDiscoverBundleInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BarBundle extends Bundle implements AutoDiscoverBundleInterface
{

}