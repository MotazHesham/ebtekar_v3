<?php

namespace App\Exceptions;

use Exception;

class UserFriendlyException extends Exception
{
    public $data;
    public function __construct($message, $data = null)
    {
        parent::__construct($message);
        $this->data = $data;
    }
}
