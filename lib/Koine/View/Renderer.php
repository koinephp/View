<?php

namespace Koine\View;

class Renderer
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function render($fileName, array $args = array())
    {
        foreach ($this->config->getPaths() as $path) {
            $file = "$path/$fileName";

            if (file_exists($file)) {
                ob_start();
                extract($args);
                include $file;
                return ob_get_clean();
            }
        }

        throw new Exceptions\FileNotFound('File \'' . $fileName . '\' was not found.');
    }
}
