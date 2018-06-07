<?php
namespace RS\DiExtraBundle\Tests\Finder;

use RS\DiExtraBundle\Finder\ClassFinder;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ClassFinderTest extends BaseTestCase
{
    public function test_ClassFinder__construct_default()
    {
        //arrange
        $dir = 'dir';

        //act
        $finder = new ClassFinder($dir);

        //assert
        $this->assertEquals($dir, $this->getObjectAttribute($finder, 'dir'));
        $this->assertNull($this->getObjectAttribute($finder, 'pattern'));
        $this->assertEquals('*.php', $this->getObjectAttribute($finder, 'pathnamePattern'));
    }

    public function test_ClassFinder__construct_with_parameters()
    {
        //arrange
        $dir = 'dir';
        $pattern = 'foo';
        $pathnamePattern = 'bar';

        //act
        $finder = new ClassFinder($dir, $pattern, $pathnamePattern);

        //assert
        $this->assertEquals($dir, $this->getObjectAttribute($finder, 'dir'));
        $this->assertEquals($pattern, $this->getObjectAttribute($finder, 'pattern'));
        $this->assertEquals($pathnamePattern, $this->getObjectAttribute($finder, 'pathnamePattern'));
    }

    public function test_ClassFinder_find_EmptyDir()
    {
        //arrange
        $dir = __DIR__."/../Fixtures/EmptyDir";
        $classFinder = new ClassFinder($dir);

        //act
        $result = $classFinder->find();

        //assert
        $this->assertCount(0, $result);
    }

    public function test_ClassFinder_find_Foo()
    {
        //arrange
        $expected = array(
            realpath(__DIR__."/../Fixtures/Foo/Bar/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar/Bar2.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar2.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar2.php"),
        );
        $dir = __DIR__."/../Fixtures/Foo";
        $classFinder = new ClassFinder($dir);

        //act
        $result = iterator_to_array($classFinder->find());
        sort($result);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_ClassFinder_find_Foo_regx()
    {
        //arrange
        $expected = array(
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar2.php"),
        );
        $dir = __DIR__."/../Fixtures/Foo";
        $pattern = '/Buz/';
        $classFinder = new ClassFinder($dir, $pattern);

        //act
        $result = iterator_to_array($classFinder->find());
        sort($result);


        //assert
        $this->assertEquals($expected, $result);
    }
}