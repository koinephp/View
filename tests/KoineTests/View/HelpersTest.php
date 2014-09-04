<?php

namespace KoineTests\View;

use Koine\View\Helpers;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class HelpersTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Helpers();
    }

    /**
     * @test
     */
    public function extendsKoineHash()
    {
        $this->assertInstanceOf('Koine\Hash', $this->object);
    }

    /**
     * @test
     */
    public function executesHelperMethodWhenInexistingMethodIsCalled()
    {
        $helper = new Helper();

        $this->object['hello'] = $helper;

        $message = $this->object->sayHello('Jon', 'Doe');

        $this->assertEquals('Hello Jon Doe.', $message);

        $message = $this->object->sayHelloAgain();

        $this->assertEquals('Hello again!', $message);
    }

    /**
     * @test
     * @expectedException Koine\NoMethodException
     */
    public function throwsExceptionWhenUndefinedMethodIsCalled()
    {
        $this->object->undefinedMethod();
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Helper 'none' was not set
     */
    public function throwsExceptionWhenHelperIsNotFound()
    {
        $this->object->fetch('none');
    }
}
