<?php

namespace App\Models;

use App\Models\Model;
use PDO;

class Coupon
{

    private $id;
    private $code;
    private $description;
    private $active;
    private $discountType;
    private $discountValue;
    private $expirationDate;
    private $usageLimit;
    private $minimumValue;
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function getDiscountType()
    {
        return $this->discountType;
    }

    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;
        return $this;
    }

    public function getDiscountValue()
    {
        return $this->discountValue;
    }

    public function setDiscountValue($discountValue)
    {
        $this->discountValue = $discountValue;
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getUsageLimit()
    {
        return $this->usageLimit;
    }

    public function setUsageLimit($usageLimit)
    {
        $this->usageLimit = $usageLimit;
        return $this;
    }

    public function getMinimumValue()
    {
        return $this->minimumValue;
    }

    public function setMinimumValue($minimumValue)
    {
        $this->minimumValue = $minimumValue;
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
        $table = 'montlink_erp.coupon';
        $statement = '
            code = ?, description = ?, active = ?, discountType = ?, discountValue = ?, 
            expirationDate = ?, usageLimit = ?, minimumValue = ?, createdAt = NOW()
        ';
        $params = [
            $this->code,
            $this->description,
            $this->active,
            $this->discountType,
            $this->discountValue,
            $this->expirationDate,
            $this->usageLimit,
            $this->minimumValue
        ];

        $q = Model::insert($table, $statement, $params);
        return $q ?: http_response_code(400);
    }

    public function listing()
    {
        $fields = 'c.*';
        $table = 'montlink_erp.coupon c';
        $statement = 'WHERE 1';
        $params = [];

        $statement .= '';
        $q = Model::select($fields, $table, $statement, $params);

        $res = [];

        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {

            $value = $r['discountType'] == 'P' ? $r['discountValue'] . '%' : 'R$ ' . number_format($r['discountValue'], 2, ',', '.');

            $res[] = [
                'id' => $r['id'],
                'code' => $r['code'],
                'description' => $r['description'],
                'active' => $r['active'] == 1
                    ? '<span class="badge bg-success">Sim</span>'
                    : '<span class="badge bg-danger">Não</span>',
                'discountType' => $r['discountType'] == 'P'
                    ? 'Percentual'
                    : 'Valor Fixo',
                'discountValue' => $value,
                'expirationDate' => date('d/m/Y', strtotime($r['expirationDate'])),
                'usageLimit' => $r['usageLimit'],
                'minimumValue' => 'R$ ' . number_format($r['minimumValue'], 2, ',', '.'),
                'createdAt' => date('d/m/Y H:i', strtotime($r['createdAt'])),
            ];
        }
        return $res;
    }

    public function update()
    {
        $table = 'montlink_erp.coupon';
        $statement = '
            code = ?, description = ?, active = ?, discountType = ?, discountValue = ?, 
            expirationDate = ?, usageLimit = ?, minimumValue = ?
        ';
        $where = 'WHERE id = ?';
        $params = [
            $this->code,
            $this->description,
            $this->active,
            $this->discountType,
            $this->discountValue,
            $this->expirationDate,
            $this->usageLimit,
            $this->minimumValue,
            $this->id
        ];

        $q = Model::update($table, $statement, $params, $where);
        return $q ?: http_response_code(400);
    }

    public function select()
    {
        $fields = 'c.*';
        $table = 'montlink_erp.coupon c';
        $where = ' WHERE id = ?';
        $params = [$this->id];

        $q = Model::select($fields, $table, $where, $params);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $table = 'montlink_erp.coupon';
        $where = 'WHERE id = ?';
        $params = [$this->id];

        $q = Model::delete($table, $where, $params);
        return $q ?: http_response_code(400);
    }

    public function getCouponByCode($couponCode)
    {
        $fields = '
            c.*, (SELECT COUNT(*) FROM montlink_erp.orders o WHERE o.couponId = c.id) AS usageCount
        ';
        $table = 'montlink_erp.coupon c';
        $where = '
        WHERE c.active = 1
        AND c.expirationDate >= CURDATE()
        AND c.code = ?
    ';
        $params = [$couponCode];

        $q = Model::select($fields, $table, $where, $params);

        $coupon = $q->fetch(PDO::FETCH_ASSOC);

        if ($coupon) {
            $coupon['usageCount'] = (int) $coupon['usageCount'];

            if ($coupon['usageLimit'] && $coupon['usageCount'] >= $coupon['usageLimit']) {
                $coupon['exceededLimit'] = true;
            } else {
                $coupon['exceededLimit'] = false;
            }
        }

        return $coupon;
    }
}
