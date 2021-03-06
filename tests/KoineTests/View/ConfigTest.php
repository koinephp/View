<?php

namespace KoineTests\View;

use Koine\View\Config;

use PHPUnit_Framework_TestCase;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Config();
    }

    /**
     * @test
     */
    public function defaultPathsIsAnEmtpyArray()
    {
        $this->assertEquals(array(), $this->object->getPaths());
    }

    /**
     * @test
     */
    public function canAddPath()
    {
        $this->object
            ->addPath('/path')
            ->addPath('/path2');

        $expected = array('/path', '/path2');

        $actual = $this->object->getPaths();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function canSetPath()
    {
        $this->object->setPaths(array('/path2'));
        $this->object->setPaths(array('/path'));
        $this->assertEquals(array('/path'), $this->object->getPaths());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage is not a string
     */
    public function addPathThrowsAnExceptionWhenArgumentIsNotAString()
    {
        $this->object->addPath(array());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage All the elements from array must be strings
     */
    public function allElementsOfArrayAreStrings()
    {
        $this->object->setPaths(array(
            '/path',
            (object) array()
        ));
    }

    /**
     * @test
     */
    public function canRegisterHelpers()
    {
        $expected = new \StdClass();

        $helper = $this->object->setHelper('myHelper', $expected)
            ->getHelper('myHelper');

        $this->assertSame($expected, $helper);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Helper 'none' was not set
     */
    public function throwsExceptionWhenHelperIsNotFound()
    {
        $this->object->getHelper('none');
    }

    /**
     * @test
     */
    public function canGetHelpers()
    {
        $expected = new \StdClass();

        $helpers = $this->object->setHelper('myHelper', $expected)
            ->getHelpers()->toArray();

        $this->assertEquals(array('myHelper' => $expected), $helpers);
    }
}
