<?php

namespace BoundaryWS\Service;

use Illuminate\Database\Connection;

class PurchaseService
{
    private const DB_TABLE = 'purchases';

    /**
     * @var Connection
     */
    private $db;

    /**
     * ProductService constructor.
     *
     * @param Connection $dbConnection
     */
    public function __construct(Connection $dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * @return array
     */
    public function getPurchases(): array
    {
        return $this->db
            ->table(self::DB_TABLE)
            ->select(
                self::DB_TABLE . '.*',
                'users.first_name',
                'users.second_name',
                'users.email_address',
                'products.*'
            )
            ->join('users', 'users.id', '=', self::DB_TABLE . '.user_id')
            ->join('products', 'products.id', '=', self::DB_TABLE . '.product_id')
            ->get()
            ->toArray();
    }
}
