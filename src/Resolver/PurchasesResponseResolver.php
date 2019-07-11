<?php

namespace BoundaryWS\Resolver;

class PurchasesResponseResolver
{
    /**
     * @param array $purchases
     *
     * @return array
     */
    public function resolve(array $purchases): array
    {
        $data = [];
        foreach ($purchases as $purchase) {
            $data[] = [
                'id' => $purchase->id,
                'customerName' => sprintf(
                    "%s %s",
                    $purchase->first_name, $purchase->second_name
                ),
                'email_address' => $purchase->email_address,
                'product' => $purchase->display_name,
                'quantity' => $purchase->quantity,
                'total' => number_format($purchase->quantity * $purchase->cost, 2),
            ];
        }

        return $data;
    }
}
