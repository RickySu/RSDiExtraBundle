<?php
namespace RS\DiExtraBundle\Tests\Finder;

use RS\DiExtraBundle\Finder\ClassFileFinder;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ClassFileFinderTest extends BaseTestCase
{
    public function test_ClassFileFinder__construct_default()
    {
        //arrange
        $dir = 'dir';

        //act
        $finder = new ClassFileFinder($dir);

        //assert
        $this->assertEquals($dir, $this->getObjectAttribute($finder, 'dir'));
        $this->assertNull($this->getObjectAttribute($finder, 'pattern'));
        $this->assertEquals('*.php', $this->getObjectAttribute($finder, 'pathnamePattern'));
    }

    public function test_ClassFileFinder__construct_with_parameters()
    {
        //arrange
        $dir = 'dir';
        $pattern = 'foo';
        $pathnamePattern = 'bar';

        //act
        $finder = new ClassFileFinder($dir);
        $finder
            ->setPattern($pattern)
            ->setPathnamePattern($pathnamePattern);

        //assert
        $this->assertEquals($dir, $this->getObjectAttribute($finder, 'dir'));
        $this->assertEquals($pattern, $this->getObjectAttribute($finder, 'pattern'));
        $this->assertEquals($pathnamePattern, $this->getObjectAttribute($finder, 'pathnamePattern'));
    }

    public function test_ClassFileFinder_find_EmptyDir()
    {
        //arrange
        $dir = __DIR__."/../Fixtures/EmptyDir";
        $classFinder = new ClassFileFinder($dir);

        //act
        $result = $classFinder->find();

        //assert
        $this->assertCount(0, $result);
    }

    public function test_ClassFileFinder_find_Foo()
    {
        //arrange
        $expected = array(
            realpath(__DIR__."/../Fixtures/Foo/Bar/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar/Bar2.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Bar2.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar2.php"),
            realpath(__DIR__."/../Fixtures/Foo/Excludes/Bar1Exclude.php"),
            realpath(__DIR__."/../Fixtures/Foo/Excludes/Bar2Exclude.php"),
        );
        $dir = __DIR__."/../Fixtures/Foo";
        $classFinder = new ClassFileFinder($dir);

        //act
        $result = iterator_to_array($classFinder->find(), false);
        sort($result);

        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_ClassFileFinder_find_Foo_exclude()
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
        $classFinder = new ClassFileFinder($dir);
        $classFinder->setExcludePathnamePattern(array("*Exclude.php"));

        //act
        $result = iterator_to_array($classFinder->find(), false);
        sort($result);
        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_ClassFileFinder_find_Foo_exclude_dir()
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
        $classFinder = new ClassFileFinder($dir);
        $classFinder->setExcludeDirPattern(array("Excludes"));

        //act
        $result = iterator_to_array($classFinder->find(), false);
        sort($result);
        //assert
        $this->assertEquals($expected, $result);
    }

    public function test_ClassFileFinder_find_Foo_regx()
    {
        //arrange
        $expected = array(
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar1.php"),
            realpath(__DIR__."/../Fixtures/Foo/Buz/Bar2.php"),
        );
        $dir = __DIR__."/../Fixtures/Foo";
        $pattern = '/Buz/';
        $classFinder = new ClassFileFinder($dir);
        $classFinder->setPattern($pattern);

        //act
        $result = iterator_to_array($classFinder->find(), false);
        sort($result);


        //assert
        $this->assertEquals($expected, $result);
    }
}