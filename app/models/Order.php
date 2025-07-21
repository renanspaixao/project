<?php

namespace App\Models;

use App\Models\Model;
use PDO;

class Order
{
    private $id;
    private $clientName;
    private $email;
    private $cep;
    private $address;
    private $city;
    private $state;
    private $subtotal;
    private $discount;
    private $shipping;
    private $total;
    private $couponId;
    private $status;
    private $createdAt;
    private $items = [];

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getClientName()
    {
        return $this->clientName;
    }
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getCep()
    {
        return $this->cep;
    }
    public function setCep($cep)
    {
        $this->cep = $cep;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function getSubtotal()
    {
        return $this->subtotal;
    }
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    public function getDiscount()
    {
        return $this->discount;
    }
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    public function getShipping()
    {
        return $this->shipping;
    }
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function getCouponId()
    {
        return $this->couponId;
    }
    public function setCouponId($couponId)
    {
        $this->couponId = $couponId;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
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


    public function addItem($productId, $variationId, $quantity, $priceUnit)
    {
        $this->items[] = [
            'productId' => $productId,
            'variationId' => $variationId,
            'quantity' => $quantity,
            'priceUnit' => $priceUnit,
            'priceTotal' => $priceUnit * $quantity
        ];
        return $this;
    }

    public function store()
    {
        $table = 'montlink_erp.orders';
        $statement = '
            clientName = ?, 
            email = ?, 
            cep = ?, 
            address = ?, 
            city = ?, 
            state = ?, 
            subtotal = ?, 
            discount = ?, 
            shipping = ?, 
            total = ?, 
            couponId = ?, 
            status = ?, 
            createdAt = NOW()
        ';

        $params = [
            $this->clientName,
            $this->email,
            $this->cep,
            $this->address,
            $this->city,
            $this->state,
            $this->subtotal,
            $this->discount,
            $this->shipping,
            $this->total,
            $this->couponId,
            $this->status ?? 'P'
        ];

        $orderId = Model::insert($table, $statement, $params);

        if (!$orderId) {
            return false;
        }

        $this->id = $orderId;

        foreach ($this->items as $item) {
            $itemTable = 'montlink_erp.orderItems';
            $itemStatement = '
                orderId = ?, 
                productId = ?, 
                variationId = ?, 
                quantity = ?, 
                priceUnit = ?, 
                priceTotal = ?
            ';
            $itemParams = [
                $orderId,
                $item['productId'],
                $item['variationId'],
                $item['quantity'],
                $item['priceUnit'],
                $item['priceTotal']
            ];

            Model::insert($itemTable, $itemStatement, $itemParams);
        }
        return $orderId;
    }

    public function listing()
    {
        $fields = 'o.*, c.code AS couponCode';
        $table = '
            montlink_erp.orders o 
            LEFT JOIN montlink_erp.coupon c ON o.couponId = c.id
        ';
        $where = 'WHERE 1';
        $params = [];

        $q = Model::select($fields, $table, $where, $params);

        $res = [];

        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {

            switch ($r['status']) {
                case 'P':
                    $statusLabel = '<span class="badge bg-warning">Pendente</span>';
                    break;
                case 'D':
                    $statusLabel = '<span class="badge bg-success">Entregue</span>';
                    break;
                case 'C':
                default:
                    $statusLabel = '<span class="badge bg-danger">Cancelado</span>';
                    break;
            }

            $res[] = [
                'id' => $r['id'],
                'clientName' => $r['clientName'],
                'email' => $r['email'],
                'cep' => $r['cep'],
                'address' => $r['address'],
                'city' => $r['city'],
                'state' => $r['state'],
                'subtotal' => 'R$ ' . number_format($r['subtotal'], 2, ',', '.'),
                'discount' => 'R$ ' . number_format($r['discount'], 2, ',', '.'),
                'shipping' => 'R$ ' . number_format($r['shipping'], 2, ',', '.'),
                'total' => 'R$ ' . number_format($r['total'], 2, ',', '.'),
                'status' => $r['status'],
                'statusLabel' => $statusLabel,
                'createdAt' => date('d/m/Y H:i', strtotime($r['createdAt']))
            ];
        }

        return $res;
    }

    public function select()
    {
        $fields = 'o.*, c.code AS couponCode';
        $table = '
            montlink_erp.orders o 
            LEFT JOIN montlink_erp.coupon c ON o.couponId = c.id
        ';
        $where = 'WHERE o.id = ?';
        $params = [$this->id];

        $q = Model::select($fields, $table, $where, $params);
        $order = $q->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return [];
        }

        $itemFields = '
            oi.*, 
            p.name AS productName, 
            pv.color AS variationColor, 
            pv.size AS variationSize
        ';
        $itemTable = '
            montlink_erp.orderItems oi
            LEFT JOIN montlink_erp.product p ON p.id = oi.productId
            LEFT JOIN montlink_erp.productVariations pv ON pv.id = oi.variationId
        ';
        $itemWhere = 'WHERE oi.orderId = ?';
        $itemParams = [$this->id];

        $qItems = Model::select($itemFields, $itemTable, $itemWhere, $itemParams);
        $items = $qItems->fetchAll(PDO::FETCH_ASSOC);

        $order['items'] = $items;

        return [$order];
    }

    public function delivered()
    {
        $table = 'montlink_erp.orders';
        $statement = 'status = "D"';
        $where = 'WHERE id = ?';
        $params = [$this->id];

        $q = Model::update($table, $statement, $params, $where);
        return $q ?: http_response_code(400);
    }


    public function cancel()
    {
        $fields = '*';
        $table = 'montlink_erp.orderItems';
        $where = 'WHERE orderId = ?';
        $params = [$this->id];

        $q = Model::select($fields, $table, $where, $params);
        $items = $q->fetchAll(PDO::FETCH_ASSOC);

        foreach ($items as $item) {
            $stock = new Stock();
            $stock->setProductId($item['productId'])
                ->setVariationId($item['variationId'])
                ->setAmount($item['quantity']);

            $stock->updatedAmount();
        }

        $table = 'montlink_erp.orders';
        $statement = 'status = ?';
        $where = 'WHERE id = ?';
        $params = ['C', $this->id];

        $q = Model::update($table, $statement, $params, $where);
        return $q ?: http_response_code(400);
    }
}
