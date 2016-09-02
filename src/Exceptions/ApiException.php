<?php

namespace Tomaj\NetteApi\Exceptions;

/**
 * API exception.
 *
 * @author Pavol Eichler
 */
class ApiException extends \Exception
{

    private $type;

    public function __construct($message = "", $code = 500, $type = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->type = $type;
    }

    public function getType(){

        return $this->type;

    }

}