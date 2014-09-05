<?php

namespace Koine\View;

use Koine\Object;
use Koine\Hash;
use Koine\View\Exceptions\FileNotFoundException;

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
     * @var hash
     */
    protected $data;

    /**
     * The view configuration
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data = new Hash();
    }

    /**
     * Render a file
     *
     * @param  string                $filename       if no extension is given default to phtml
     * @param  array                 $localVariables
     * @throws FileNotFoundException when file does not exist in the view paths
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
     * @param  string                $filename       if no extension is given
     *                                               default to phtml
     * @param  array                 $localVariables
     * @throws FileNotFoundException when file does not exist in the view paths
     * @throws Exception             when errors are issued
     * @return string
     */
    public function partial($name, array $localVariables = array())
    {
        $parts   = explode('/', $name);
        $name    = array_pop($parts);
        $parts[] = "_$name";
        $partial = implode('/', $parts);

        return $this->render($partial, $localVariables);
    }

    /**
     * @param  string    $file
     * @params array $localVariables
     * @throws Exception when errors are issued
     */
    protected function includeWithLocalVariables($file, array $locals = array())
    {
        ob_start();

        set_error_handler(array(
            'Koine\View\Exceptions\Exception',
            'handleError'
        ));

        try {
            extract($locals);
            include $file;
        } catch (\Exception $e) {
            ob_get_clean();
            restore_error_handler();
            throw $e;
        }

        restore_error_handler();

        return ob_get_clean();
    }

    /**
     * Returns the path for the given filename.
     *
     * @throws FileNotFoundException when file does not exist in the view paths
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

        throw new FileNotFoundException(
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

        $helpers = $this->getConfig()->getHelpers();

        return call_user_func_array(array($helpers, $method), $args);
    }

    /**
     * Sets the renderer data
     * @param  array $data
     * @return self
     */
    public function setData(array $data)
    {
        foreach ($this->getData()->toArray() as $key => $value) {
            $this->getData()->offsetUnset($key);
        }

        $this->addData($data);

        return $this;
    }

    /**
     * Adds data to the renderer
     * @param  array $data
     * @return self
     */
    public function addData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the collection of data
     * @return Hash
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get data
     *
     * @param  string $dataKey
     * @param  mixed  $default
     * @return mixed
     */
    public function get($dataKey, $default = null)
    {
        return $this->fetch($dataKey, function ($key) use ($default) {
            return $default;
        });
    }

    /**
     * Get data
     *
     * @param  string                    $dataKey
     * @param  mixed                     $default
     * @return mixed
     * @throws \InvalidArgumentException when data is not set
     */
    public function fetch($dataKey, $default = null)
    {
        return $this->getData()->fetch($dataKey, $default);
    }

    /**
     * Get data by name
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->fetch($name);
    }
}
