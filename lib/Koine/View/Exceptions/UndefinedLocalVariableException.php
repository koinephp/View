<?php

namespace Koine\View\Exceptions;

use Exception;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class UndefinedLocalVariableException extends Exception
{
    /**
     * Constructor.
     *
     * @param string    $message
     * @param integer   $code
     * @param string    $file
     * @param integer   $line
     * @param Exception $previous
     */
    public function __construct($message, $code, $file, $line, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->file  = $file;
        $this->line  = $line;
    }

    public static function handleError($errno, $errstr, $errfile = null, $errline = null)
    {
        $msg = $errstr . " in file $errfile:$errline";

        throw new self($msg, $errno, $errfile, $errline);
    }
}
