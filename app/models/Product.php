<?php

namespace App\Models;

use App\Models\Model;
use PDO;

class Product
{
    private $id;
    private $name;
    private $price;
    private $createdAt;
    private $variations = [];


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setVariations(array $variations)
    {
        $this->variations = $variations;
        return $this;
    }

    public function store()
    {
        $table = 'montlink_erp.product';
        $statement = 'name = ?, price = ?, createdAt = NOW()';
        $params = [$this->name, $this->price];

        $productId = Model::insert($table, $statement, $params);

        if (!$productId) {
            http_response_code(400);
            return false;
        }

        foreach ($this->variations as $var) {
            $variationTable = 'montlink_erp.productVariations';
            $variationStmt = 'productId = ?, color = ?, size = ?, price = ?, createdAt = NOW()';

            $price = isset($var['price']) && is_numeric($var['price']) ? (float)$var['price'] : null;
            $stockAmount = isset($var['stock']) && is_numeric($var['stock']) ? (int)$var['stock'] : null;

            $variationParams = [
                $productId,
                $var['color'] ?? null,
                $var['size'] ?? null,
                $price
            ];

            $variationId = Model::insert($variationTable, $variationStmt, $variationParams);

            if ($variationId && $stockAmount !== null) {
                $s = new Stock();
                $s->setProductId($productId)
                    ->setVariationId($variationId)
                    ->setAmount($stockAmount);
                $s->store();
            }
        }

        return ['id' => $productId];
    }



    public function listing()
    {
        $fields = 'p.*';
        $table = 'montlink_erp.product p';
        $statement = 'WHERE 1';
        $params = [];

        $q = Model::select($fields, $table, $statement, $params);

        $res = [];
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $res[] = [
                'id' => $r['id'],
                'name' => $r['name'],
                'price' => 'R$ ' . number_format($r['price'], 2, ',', '.')
            ];
        }

        return $res;
    }

    public function update()
    {
        $table = 'montlink_erp.product';
        $statement = 'name = ?, price = ?';
        $where = 'WHERE id = ?';

        $price = is_numeric($this->price) ? (float)$this->price : 0;

        $params = [$this->name, $price, $this->id];

        $updated = Model::update($table, $statement, $params, $where);

        if (!$updated) {
            http_response_code(400);
            return false;
        }

        Model::delete('montlink_erp.productVariations', 'WHERE productId = ?', [$this->id]);
        (new Stock())->deleteByProductId($this->id);

        if (!empty($this->variations)) {
            foreach ($this->variations as $var) {
                $variationTable = 'montlink_erp.productVariations';
                $variationStmt = 'productId = ?, color = ?, size = ?, price = ?, createdAt = NOW()';

                $price = isset($var['price']) && is_numeric($var['price']) ? (float)$var['price'] : null;
                $stockAmount = isset($var['stock']) && is_numeric($var['stock']) ? (int)$var['stock'] : null;

                $variationParams = [
                    $this->id,
                    $var['color'] ?? null,
                    $var['size'] ?? null,
                    $price
                ];

                $variationId = Model::insert($variationTable, $variationStmt, $variationParams);

                if ($variationId && $stockAmount !== null) {
                    $s = new Stock();
                    $s->setProductId($this->id)
                        ->setVariationId($variationId)
                        ->setAmount($stockAmount);
                    $s->store();
                }
            }
        }

        return ['id' => $this->id];
    }

    public function select()
    {
        $fields = 'p.*';
        $table = 'montlink_erp.product p';
        $where = 'WHERE id = ?';
        $params = [$this->id];

        $query = Model::select($fields, $table, $where, $params);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $table = 'montlink_erp.product';
        $where = 'WHERE id = ?';
        $params = [$this->id];

        $q = Model::delete($table, $where, $params);
        return $q ?: http_response_code(400);
    }


    public function getVariationsByProductId($productId)
    {
        $fields = 'pv.*, s.amount as stock';
        $table = '
            montlink_erp.productVariations pv
            LEFT JOIN montlink_erp.stock s ON s.variationId = pv.id
        ';
        $where = 'WHERE pv.productId = ?';
        $params = [$productId];

        $q = Model::select($fields, $table, $where, $params);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }
}
