<?php

namespace Koine\View;

use Koine\Hash;
use InvalidArgumentException;

class Config
{
    /**
     * @var array
     */
    protected $paths = array();

    /**
     * @var Hash
     */
    protected $helpers;

    public function __construct()
    {
        $this->helpers = new Hash();
    }

    /**
     * Get the paths
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Add a path
     *
     * @param  string $path
     * @return self
     */
    public function addPath($path)
    {
        $this->ensureString($path, 'Argument path is not a string');
        $this->paths[] = $path;

        return $this;
    }

    /**
     * Set paths
     *
     * @param  array $paths
     * @return self
     */
    public function setPaths(array $paths)
    {
        foreach ($paths as $value) {
            $this->ensureString(
                $value,
                'All the elements from array must be strings'
            );
        }

        $this->paths = $paths;

        return $this;
    }

    /**
     * Ensure element is a string
     * @param  mixed                    $element
     * @param  string                   $message the message to be thrown in the exception
     * @throws InvalidArgumentException when element is not a string
     */
    protected function ensureString($element, $message)
    {
        if (gettype($element) !== 'string') {
            throw new InvalidArgumentException($message);
        }
    }

    /**
     * Set a helper to be used in the views
     *
     * @param string $name
     * @param mixed  $helper
     */
    public function setHelper($name, $helper)
    {
        $this->helpers[$name] = $helper;

        return $this;
    }

    /**
     * Get a helper
     * @param  string                   $name
     * @return mixed
     * @throws InvalidArgumentException when helper was not set
     */
    public function getHelper($name)
    {
        return $this->helpers->fetch($name, function ($name) {
            throw new InvalidArgumentException("Helper '$name' was not set");
        });
    }

    /**
     * Get the collection of helpers
     * @return Hash
     */
    public function getHelpers()
    {
        return $this->helpers;
    }
}
