<?php

namespace Koine\View;

use Koine\Hash;
use ReflectionClass;
use InvalidArgumentException;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Helpers extends Hash
{
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

        foreach ($this as $helper) {
            $class = new ReflectionClass($helper);

            if ($class->hasMethod($method)) {
                return $class->getMethod($method)->invokeArgs($helper, $args);
            }
        }

        return parent::send($method);
    }

    public function fetch($name, $default = null)
    {
        if ($default === null) {
            $default = function ($name) {
                throw new InvalidArgumentException("Helper '$name' was not set");
            };
        }
        return parent::fetch($name, $default);
    }
}
