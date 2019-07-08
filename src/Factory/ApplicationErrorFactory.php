<?php

namespace BoundaryWS\Factory;

use BoundaryWS\Error\ApplicationError;

class ApplicationErrorFactory {
    /**
     * @param string     $errorCode
     * @param string     $title
     * @param string     $detail
     * @param integer    $status
     * @param array|null $source
     * @return ApplicationError
     */
    public static function create(
        string $errorCode,
        string $title,
        string $detail,
        int $status,
        array $source = null
    ): ApplicationError {
        return new ApplicationError($errorCode, $title, $detail, $status, $source);
    }
}