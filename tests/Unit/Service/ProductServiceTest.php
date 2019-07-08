<?php

namespace Tests\Unit\Service;

use BoundaryWS\Service\ProductService;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * @var ProductService
     */
    private $subject;

    /**
     * @var Connection|MockObject
     */
    private $db;

    /**
     * @var Builder|MockObject
     */
    private $qb;

    public function setUp()
    {
        $this->db = $this->createMock(Connection::class);
        $this->qb = $this->createMock(Builder::class);

        $this->db
            ->method('table')
            ->with('products')
            ->willReturn($this->qb);

        $this->subject = new ProductService($this->db);
    }

    public function testCreateProduct()
    {
        $displayName = "PROD 1";
        $cost = "1.99";

        $this->qb
            ->expects($this->once())
            ->method('insert')
            ->with(['display_name' => $displayName, 'cost' => $cost])
            ->willReturn(true);

        $this->assertTrue($this->subject->createProduct($displayName, $cost));
    }

    public function testGetProducts()
    {
        $results = [
            [
                'id' => 1,
                'display_name' => 'PROD 1',
                'cost' => '1.99',
            ]
        ];

        $collection = $this->createMock(Collection::class);
        $collection
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($results);

        $this->qb
            ->expects($this->once())
            ->method('get')
            ->willReturn($collection);

        $this->qb
            ->expects($this->once())
            ->method('select')
            ->willReturn($this->qb);

        $products = $this->subject->getProducts();

        $this->assertEquals($results, $products);
    }
}
