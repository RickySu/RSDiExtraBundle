<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use RS\DiExtraBundle\Annotation\DoctrineRepository;
use RS\DiExtraBundle\Converter\Annotation\DoctrineRepositoryClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class DoctrineRepositoryClassHandlerTest extends BaseTestCase
{
    /**
     * @expectedException \RS\DiExtraBundle\Exception\InvalidAnnotationException
     */
    public function test_handle_invalid_instance()
    {
        //arrange
        $repository = $this->getMockBuilder(ServiceEntityRepository::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $doctrineRepository = new DoctrineRepository();
        $doctrineRepositoryHandler = new DoctrineRepositoryClassHandler();

        //act
        $doctrineRepositoryHandler->handle($classMeta, new \ReflectionClass($this), $doctrineRepository);

        //assert
        $this->assertTrue($classMeta->private);
        $this->assertTrue($classMeta->autowire);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'doctrine.repository_service' => array(array()),
        ), $classMeta->tags);
    }

    public function test_handle_no_service_define()
    {
        //arrange
        $repository = $this->getMockBuilder(ServiceEntityRepository::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $doctrineRepository = new DoctrineRepository();
        $doctrineRepositoryHandler = new DoctrineRepositoryClassHandler();

        //act
        $doctrineRepositoryHandler->handle($classMeta, new \ReflectionClass($repository), $doctrineRepository);

        //assert
        $this->assertTrue($classMeta->private);
        $this->assertTrue($classMeta->autowire);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'doctrine.repository_service' => array(array()),
        ), $classMeta->tags);
    }

    public function est_handle_has_service_define()
    {
        //arrange
        $repository = $this->getMockBuilder(ServiceEntityRepository::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        $doctrineRepository = new DoctrineRepository();
        $doctrineRepositoryHandler = new DoctrineRepositoryClassHandler();

        //act
        $doctrineRepositoryHandler->handle($classMeta, new \ReflectionClass($repository), $doctrineRepository);


        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'doctrine.repository_service' => array(array()),
        ), $classMeta->tags);
    }

}
