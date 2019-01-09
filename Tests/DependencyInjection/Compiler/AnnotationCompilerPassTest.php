<?php
namespace RS\DiExtraBundle\Test\DependencyInjection\Compiler;

use RS\DiExtraBundle\DependencyInjection\Compiler\AnnotationCompilerPass;
use RS\DiExtraBundle\Tests\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AnnotationCompilerPassTest extends BaseTestCase
{
    public function test_getSearchDirectories_allbundles()
    {
        //arrange
        $parameters = array(
            'kernel.bundles' => array(
                'foo' => 'fooClass',
                'bar' => 'barClass',
                'buz' => 'buzClass',
            ),
            'rs_di_extra.all_bundles' => true,
            'rs_di_extra.bundles' => array(),
            'rs_di_extra.disallow_bundles' => array(),
            'rs_di_extra.directories' => array(
                'foo',
                'bar'
            ),
        );
        $expected = array(
            'foo',
            'bar',
            'path:fooClass',
            'path:barClass',
            'path:buzClass',
        );
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(array('getParameter'))
            ->disableOriginalConstructor()
            ->getMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('getParameter')
            ->willReturnCallback(function($key) use($parameters){
                return $parameters[$key];
            });
        $annotationCompilerPass = $this->getMockBuilder(AnnotationCompilerPass::class)
            ->setMethods(array('findBundleDirectory'))
            ->getMock();
        $annotationCompilerPass
            ->expects($this->any())
            ->method('findBundleDirectory')
            ->willReturnCallback(function($bundleClass){
                return "path:$bundleClass";
            });

        //act
        $result = $this->callObjectMethod($annotationCompilerPass, 'getSearchDirectories', $container);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_getSearchDirectories_disallow_bundles()
    {
        //arrange
        $parameters = array(
            'kernel.bundles' => array(
                'foo' => 'fooClass',
                'bar' => 'barClass',
                'buz' => 'buzClass',
            ),
            'rs_di_extra.all_bundles' => true,
            'rs_di_extra.bundles' => array(),
            'rs_di_extra.disallow_bundles' => array(
                'foo',
                'bar'
            ),
            'rs_di_extra.directories' => array(
                'foo',
                'bar'
            ),
        );
        $expected = array(
            'foo',
            'bar',
            'path:buzClass',
        );
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(array('getParameter'))
            ->disableOriginalConstructor()
            ->getMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('getParameter')
            ->willReturnCallback(function($key) use($parameters){
                return $parameters[$key];
            });
        $annotationCompilerPass = $this->getMockBuilder(AnnotationCompilerPass::class)
            ->setMethods(array('findBundleDirectory'))
            ->getMock();
        $annotationCompilerPass
            ->expects($this->any())
            ->method('findBundleDirectory')
            ->willReturnCallback(function($bundleClass){
                return "path:$bundleClass";
            });

        //act
        $result = $this->callObjectMethod($annotationCompilerPass, 'getSearchDirectories', $container);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_getSearchDirectories_allow_bundles()
    {
        //arrange
        $parameters = array(
            'kernel.bundles' => array(
                'foo' => 'fooClass',
                'bar' => 'barClass',
                'buz' => 'buzClass',
            ),
            'rs_di_extra.all_bundles' => false,
            'rs_di_extra.bundles' => array(
                'foo',
                'bar'
            ),
            'rs_di_extra.disallow_bundles' => array(),
            'rs_di_extra.directories' => array(
                'foo',
                'bar'
            ),
        );
        $expected = array(
            'foo',
            'bar',
            'path:fooClass',
            'path:barClass',
        );
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(array('getParameter'))
            ->disableOriginalConstructor()
            ->getMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('getParameter')
            ->willReturnCallback(function($key) use($parameters){
                return $parameters[$key];
            });
        $annotationCompilerPass = $this->getMockBuilder(AnnotationCompilerPass::class)
            ->setMethods(array('findBundleDirectory'))
            ->getMock();
        $annotationCompilerPass
            ->expects($this->any())
            ->method('findBundleDirectory')
            ->willReturnCallback(function($bundleClass){
                return "path:$bundleClass";
            });

        //act
        $result = $this->callObjectMethod($annotationCompilerPass, 'getSearchDirectories', $container);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_findBundleDirectory()
    {
        //arrange
        $annotationCompilerPass = new AnnotationCompilerPass();
        $className = self::class;

        //act
        $result = $this->callObjectMethod($annotationCompilerPass, 'findBundleDirectory', $className);

        //assert
        $this->assertEquals(__DIR__, $result);
    }

    public function test_findClassFiles()
    {
        //arrange
        $directories = array(
            __DIR__.'/../../Fixtures/Foo/Bar',
            __DIR__.'/../../Fixtures/Foo/Buz',
            __DIR__.'/../../Fixtures/Foo/Excludes',
        );
        $expected = array(
            realpath(__DIR__.'/../../Fixtures/Foo/Bar/Bar1.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Bar/Bar2.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Buz/Bar1.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Buz/Bar2.php'),
        );
        sort($expected);

        $annotationCompilerPass = new AnnotationCompilerPass();

        //act
        $result = iterator_to_array($this->callObjectMethod($annotationCompilerPass, 'findClassFiles', $directories, null, '*Exclude.php'), false);
        sort($result);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_findClassFiles_exclude_dir()
    {
        //arrange
        $directories = array(
            __DIR__.'/../../Fixtures/Foo',
        );
        $expected = array(
            realpath(__DIR__.'/../../Fixtures/Foo/Buz/Bar1.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Buz/Bar2.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Bar/Bar1.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Bar/Bar2.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Bar1.php'),
            realpath(__DIR__.'/../../Fixtures/Foo/Bar2.php'),
        );
        sort($expected);
        $annotationCompilerPass = new AnnotationCompilerPass();

        //act
        $result = iterator_to_array($this->callObjectMethod($annotationCompilerPass, 'findClassFiles', $directories, 'Excludes', null), false);
        sort($result);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_handleClassFiles()
    {
        //arrange

        //act
        //assert
    }
}
