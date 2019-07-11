<?php

namespace Tests\Unit\Resolver;

use BoundaryWS\Resolver\PurchasesResponseResolver;
use PHPUnit\Framework\TestCase;

class PurchasesResponseResolverTest extends TestCase
{
    /**
     * @var PurchasesResponseResolver
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new PurchasesResponseResolver();
    }

    public function testResolve(): void
    {
        $purchases = [];
        $expectedData = [];

        for ($i = 1; $i <= 3; $i++) {
            $purchase = new \stdClass();
            $purchase->id = $i;
            $purchase->first_name = "John";
            $purchase->second_name = "Doe{$i}";
            $purchase->email_address = sprintf(
                "%s.%s@example.com",
                $purchase->first_name,
                $purchase->second_name
            );
            $purchase->display_name = "Product {$i}";
            $purchase->quantity = $i;
            $purchase->cost = rand(1, 5);

            $data = [
                'id' => $i,
                'customerName' => sprintf(
                    "%s %s",
                    $purchase->first_name,
                    $purchase->second_name
                ),
                'email_address' => $purchase->email_address,
                'product' => $purchase->display_name,
                'quantity' => $purchase->quantity,
                'total' => number_format($purchase->quantity * $purchase->cost, 2),
            ];

            $expectedData[] = $data;
            $purchases[] = $purchase;
        }

        $this->assertSame($expectedData, $this->subject->resolve($purchases));
    }
}
