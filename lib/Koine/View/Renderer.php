<?php

namespace Koine\View;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class Renderer
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
}
