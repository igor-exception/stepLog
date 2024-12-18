<?php
namespace APP\Exceptions;

class RepositoryException extends \Exception
{
    public function __construct($message = 'Erro ao registrar', $code = 0, \Exception $previous= null)
    {
        parent::__construct($message, $code, $previous);
    }
}