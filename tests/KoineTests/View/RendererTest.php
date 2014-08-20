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
        $this->config->addPath(FIXTURES_PATH . '/partial');
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
    public function renderFileWithExtensionDefaultToPhtml()
    {
        $file = 'HelloWorld';
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

    /**
     * @test
     */
    public function renderPartials()
    {
        $expected = file_get_contents(FIXTURES_PATH . '/partial/partial_test.phtml');

        $actual = $this->object->render('posts.phtml', array(
            'title' => 'Partial Test',
            'body'  => 'Post Content',
            'relatedPosts' => array(
                array('title' => 'Post 1', 'url' => 'http://test.com/1-post'),
                array('title' => 'Post 2', 'url' => 'http://test.com/2-post'),
            )
        ));

        // Fix indentation differences
        $expected = preg_replace('/\s+/', '', $expected);
        $actual   = preg_replace('/\s+/', '', $actual);

        $this->assertEquals($expected, $actual);
    }
}
