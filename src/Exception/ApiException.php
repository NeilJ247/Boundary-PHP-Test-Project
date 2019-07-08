<?php

namespace BoundaryWS\Exception;

// TODO this needs a unit test

class ApiException extends \Exception {
    /**
     * @var ApplicationError[]
     */
    protected $errors;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * ApiException constructor.
     *
     * @param ApplicationError[] $errors
     * @param int                $statusCode
     */
    public function __construct(array $errors, int $statusCode)
    {
        parent::__construct('', 0);

        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    /**
     * @return ApplicationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }
}