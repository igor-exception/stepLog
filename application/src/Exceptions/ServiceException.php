<?php
namespace APP\Exceptions;
use Exception;

class ServiceException extends Exception
{
    public function __construct(string $message = "Erro ao executar serviço", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}