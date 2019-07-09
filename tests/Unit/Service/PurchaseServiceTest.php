<?php

namespace Tests\Unit\Service;

use BoundaryWS\Service\PurchaseService;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PurchaseServiceTest extends TestCase
{
    /**
     * @var PurchaseService
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
            ->with('purchases')
            ->willReturn($this->qb);

        $this->subject = new PurchaseService($this->db);
    }

    public function testGetPurchases(): void
    {
        $results = [
            [
                'user_id' => 11,
                'product_id' => 22,
                'quantity' => 10,
            ]
        ];

        $this->qb
            ->expects($this->once())
            ->method('select')
            ->with(
                'purchases.*',
                'users.first_name',
                'users.second_name',
                'users.email_address',
                'products.*'
            )
            ->willReturn($this->qb);

        // Normally do this with returnValueMap but it wasn't playing nice.
        $this->qb
            ->expects($this->at(1))
            ->method('join')
            ->with(
                'users',
                'users.id',
                '=',
                'purchases.user_id',
                'inner',
                false
            )
            ->willReturn($this->qb);

        $this->qb
            ->expects($this->at(2))
            ->method('join')
            ->with(
                'products',
                'products.id',
                '=',
                'purchases.product_id',
                'inner',
                false
            )
            ->willReturn($this->qb);

        $collection = $this->createMock(Collection::class);
        $collection
            ->expects($this->once())
            ->method('toArray')
            ->willReturn($results);

        $this->qb
            ->expects($this->once())
            ->method('get')
            ->with(['*'])
            ->willReturn($collection);

        $products = $this->subject->getPurchases();

        $this->assertEquals($results, $products);
    }
}
