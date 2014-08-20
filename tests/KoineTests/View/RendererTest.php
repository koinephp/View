<?php

namespace KoineTests\View;

use Koine\View\Config;
use Koine\View\Renderer;
use PHPUnit_Framework_TestCase;

class RendererTest extends PHPUnit_Framework_TestCase
{
    protected $object;
    protected $config;

    public function setUp()
    {
        $this->config = new Config();
        $this->config->addPath(FIXTURES_PATH);
        $this->object = new Renderer($this->config);
    }

    /**
     * @test
     * @expectedException \Koine\View\Exceptions\FileNotFound
     * @expectedExceptionMessage File 'foo.php' was not found.
     */
    public function renderThrowsExceptionWhenFileDoesNotExist()
    {
        $this->object->render('foo.php');
    }

    /**
     * @test
     */
    public function renderSimpleFileWithoutParameters()
    {
        $file = 'HelloWorld.phtml';
        $this->assertEquals('Hello World!', $this->object->render($file));
    }

    /**
     * @test
     */
    public function renderContentsWithParameters()
    {
        $expected = "Hello Jon Doe!";

        $actual = $this->object->render(
            'hey_you.phtml',
            array(
                'name'     => 'Jon',
                'lastname' => 'Doe',
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /*
     * @test
     */
    public function renderPartials()
    {

    }
  }
