<?php

namespace App\Models;

use App\Models\Model;
use PDO;

class Stock
{
    private $id;
    private $productId;
    private $variationId;
    private $amount;
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    public function getVariationId()
    {
        return $this->variationId;
    }

    public function setVariationId($variationId)
    {
        $this->variationId = $variationId;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
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


    public function store()
    {
        $table = 'montlink_erp.stock';
        $statement = 'productId = ?, variationId = ?, amount = ?, createdAt = NOW()';
        $params = [$this->productId, $this->variationId, $this->amount];

        $q = Model::insert($table, $statement, $params);
        return $q ?: http_response_code(400);
    }

    public function deleteByProductId($productId)
    {
        return Model::delete('montlink_erp.stock', 'WHERE productId = ?', [$productId]);
    }

    public function deleteByVariationId($variationId)
    {
        return Model::delete('montlink_erp.stock', 'WHERE variationId = ?', [$variationId]);
    }

    public function listing()
    {
        $fields = '
        p.id as productId,
        p.name as productName,
        p.price as productPrice,
        pv.id as variationId,
        pv.color,
        pv.size,
        pv.price as variationPrice,
        COALESCE(pv.price, p.price) as finalPrice,
        s.amount as stock
    ';

        $table = 'montlink_erp.stock s
              LEFT JOIN montlink_erp.product p ON p.id = s.productId
              LEFT JOIN montlink_erp.productVariations pv ON pv.id = s.variationId';

        $where = 'WHERE s.amount > 0';
        $params = [];

        $q = Model::select($fields, $table, $where, $params);

        $res = [];
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $res[] = [
                'productId' => $r['productId'],
                'productName' => $r['productName'],
                'variationId' => $r['variationId'],
                'color' => $r['color'],
                'size' => $r['size'],
                'stock' => $r['stock'],
                'price' => $r['finalPrice']
            ];
        }
        return $res;
    }


public function updatedAmount()
{
    $table = 'montlink_erp.stock';
    $statement = 'amount = amount + ?';
    $where = 'WHERE productId = ? AND variationId = ?';
    $params = [$this->amount, $this->productId, $this->variationId];
    
    $q = Model::update($table, $statement, $params, $where);
    
    return $q ?: http_response_code(400);
}




}
