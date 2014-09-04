<?php

namespace Koine\View;

use Koine\Object;
use ReflectionClass;
use ReflectionException;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Renderer extends Object
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * The view configuration
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Render a file
     *
     * @param  string                 $filename       if no extension is given default to phtml
     * @param  array                  $localVariables
     * @throws Exception\FileNotFound when file does not exist in the view paths
     * @return string
     */
    public function render($filename, array $localVariables = array())
    {
        return $this->includeWithLocalVariables(
            $this->getFilePath($filename),
            $localVariables
        );
    }

    /**
     * Render a partial. A partial is a file prefixed with the "_" (underscore)
     * prefix
     *
     * @param  string                 $filename       if no extension is given
     *                                                default to phtml
     * @param  array                  $localVariables
     * @throws Exception\FileNotFound when file does not exist in the view paths
     * @return string
     */
    public function partial($name, array $localVariables = array())
    {
        return $this->render("_$name", $localVariables);
    }

    protected function includeWithLocalVariables($file, array $locals = array())
    {
        ob_start();

        try {
            extract($locals);
            include $file;
        } catch (\Exception $e) {
            ob_get_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    /**
     * Returns the path for the given filename.
     *
     * @throws Exception\FileNotFound when file does not exist in the view paths
     * @return string
     */
    public function getFilePath($filename)
    {
        if (!strpos($filename, '.')) {
            $filename .= ".phtml";
        }

        foreach ($this->config->getPaths() as $path) {
            $file = "$path/$filename";

            if (file_exists($file)) {
                return $file;
            }
        }

        throw new Exceptions\FileNotFound(
            'File \'' . $filename . '\' was not found.'
        );
    }

    /**
     * Get the view config
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Delegates missing method to the helpers
     *
     * {@inheritdocs}
     */
    public function send()
    {
        $arguments = func_get_args();
        $method    = array_shift($arguments);
        $args      = array_shift($arguments);

        foreach ($this->getConfig()->getHelpers() as $helper) {
            $class = new ReflectionClass($helper);

            if ($class->hasMethod($method)) {
                return $class->getMethod($method)->invokeArgs($helper, $args);
            }
        }

        return parent::send($method);
    }
}