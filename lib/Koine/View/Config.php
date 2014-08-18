<?php

namespace Koine\View;

class Config
{
    /**
     * @var array
     */
    protected $paths = array();

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
     * @param string $path
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
     * @param array $paths
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

    protected function ensureString($element, $message)
    {
        if (gettype($element) !== 'string') {
            throw new \InvalidArgumentException($message);
        }
    }
}
