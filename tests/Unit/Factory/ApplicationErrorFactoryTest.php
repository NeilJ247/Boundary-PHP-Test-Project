<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use BoundaryWS\Factory\ApplicationErrorFactory;
use BoundaryWS\Error\ApplicationError;

class ApplicationErrorFactoryTest extends TestCase {
    
    /**
     * @dataProvider errorDataProvider
     *
     * @param string $errorCode
     * @param string $title
     * @param string $detail
     * @param integer $status
     * @param array $source
     * @return void
     */
    public function testCreate(
        string $errorCode,
        string $title,
        string $detail,
        int $status,
        array $source = null
    ) : void {
        $applicationError = ApplicationErrorFactory::create($errorCode, $title, $detail, $status, $source);

        $this->assertInstanceOf(ApplicationError::class, $applicationError);
        $this->assertNotNull($applicationError->getId());
        $this->assertEquals($errorCode, $applicationError->getErrorCode());
        $this->assertEquals($title, $applicationError->getTitle());
        $this->assertEquals($detail, $applicationError->getDetail());
        $this->assertEquals($status, $applicationError->getStatus());
        $this->assertEquals($source, $applicationError->getSource());
    }

    public function errorDataProvider(): array
    {
        return [
            "without source" => [
                'ERR-1',
                'TITLE',
                'DETAIL',
                400,
                null,
            ],
            "with source" => [
                'ERR-1',
                'TITLE',
                'DETAIL',
                400,
                ['pointer' => '/data'],
            ]
        ];
    }
}
