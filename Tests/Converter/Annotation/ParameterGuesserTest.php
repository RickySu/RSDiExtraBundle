<?php
namespace RS\DiExtraBundle\Tests\Converter\Annotation;


use RS\DiExtraBundle\Converter\Annotation\ParameterGuesser;
use RS\DiExtraBundle\Tests\BaseTestCase;

class ParameterGuesserTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider_isTagged
     */
    public function test_isTagged($status, $name)
    {
        //arrange
        $parameterGuesser = new ParameterGuesser();

        //act
        $result = $this->callObjectMethod($parameterGuesser, 'isTagged', $name);

        //assert
        $this->assertEquals($status, $result);
    }

    public function dataProvider_isTagged()
    {
        return array(
            array(
                'status' => true,
                'name' => '!tagged debug',
            ),
            array(
                'status' => false,
                'name' => '!tagged_something',
            ),
            array(
                'status' => false,
                'name' => 'debug',
            ),
        );
    }

    /**
     * @dataProvider dataProvider_isParameters
     */
    public function test_isParameters($status, $name)
    {
        //arrange
        $parameterGuesser = new ParameterGuesser();

        //act
        $result = $this->callObjectMethod($parameterGuesser, 'isParameters', $name);

        //assert
        $this->assertEquals($status, $result);
    }

    public function dataProvider_isParameters()
    {
        return array(
            array(
                'status' => true,
                'name' => '%debug%',
            ),
            array(
                'status' => false,
                'name' => '%debug',
            ),
            array(
                'status' => false,
                'name' => 'debug%',
            ),
            array(
                'status' => false,
                'name' => 'debug',
            ),
        );
    }

    /**
     * @dataProvider dataProvider_camelToSnake
     */
    public function test_camelToSnake($camel, $snake)
    {
        //arrange
        $parameterGuesser = new ParameterGuesser();

        //act
        $result = $this->callObjectMethod($parameterGuesser, 'camelToSnake', $camel);

        //assert
        $this->assertEquals($snake, $result);
    }

    public function dataProvider_camelToSnake()
    {
        return array(
            array(
                'camel' => 'AAbC',
                'snake' => 'a_ab_c',
            ),
            array(
                'camel' => '__AAbC',
                'snake' => 'a_ab_c',
            ),

            array(
                'camel' => 'RsDiExtraBundle',
                'snake' => 'rs_di_extra_bundle',
            ),
        );
    }
}