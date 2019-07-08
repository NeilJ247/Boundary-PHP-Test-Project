<?php

namespace BoundaryWS\Service;

use Illuminate\Database\Connection;

class ProductService
{
    private CONST DB_TABLE = 'products';

    /**
     * @var Connection
     */
    private $db;

    /**
     * ProductService constructor.
     *
     * @param Connection $dbConnection
     */
    public function __construct(Connection $dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * @param string $displayName
     * @param string $cost
     *
     * @return bool
     */
    public function createProduct(string $displayName, string $cost): bool
    {
        return $this->db
            ->table(self::DB_TABLE)
            ->insert(['display_name' => $displayName, 'cost' => $cost]);
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->db
            ->table(self::DB_TABLE)
            ->select()
            ->get()
            ->toArray();
    }
}
