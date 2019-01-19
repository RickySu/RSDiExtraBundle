<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;

use RS\DiExtraBundle\Annotation\Command;
use RS\DiExtraBundle\Converter\Annotation\CommandClassHandler;
use RS\DiExtraBundle\Converter\ClassMeta;
use RS\DiExtraBundle\Tests\BaseTestCase;

class CommandClassHandlerTest extends BaseTestCase
{
    public function test_handle_no_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;

        $command = new Command();

        $commandHandler = new CommandClassHandler();

        //act
        $commandHandler->handle($classMeta, new \ReflectionClass($this), $command);

        //assert
        $this->assertFalse($classMeta->public);
        $this->assertEquals(self::class, $classMeta->id);
        $this->assertEquals(array(
            'console.command' => array(
                array(),
            ),
        ), $classMeta->tags);
    }

    public function test_handle_has_service_define()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        $command = new Command();

        $commandHandler = new CommandClassHandler();

        //act
        $commandHandler->handle($classMeta, new \ReflectionClass($this), $command);

        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'console.command' => array(
                array(),
            ),
        ), $classMeta->tags);
    }

    public function test_handle_custom_command_defined()
    {
        //arrange
        $classMeta = new ClassMeta();
        $classMeta->class = self::class;
        $classMeta->id = 'foo';

        $command = new Command();
        $command->command = 'bar';

        $commandHandler = new CommandClassHandler();

        //act
        $commandHandler->handle($classMeta, new \ReflectionClass($this), $command);

        //assert
        $this->assertEquals('foo', $classMeta->id);
        $this->assertEquals(array(
            'console.command' => array(
                array(
                    'command' => 'bar'
                ),
            ),
        ), $classMeta->tags);
    }
}
