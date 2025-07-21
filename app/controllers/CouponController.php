<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $this->view('coupon/index');
    }

    public function create()
    {
        $this->view('coupon/create');
    }

    public function show()
    {
        $this->view('coupon/show');
    }

    public function edit()
    {
        $this->view('coupon/edit');
    }

    public function store()
    {
        $discountType = $_POST['discountType'];
        $discountValue = floatval($_POST['discountValue']);
        $minimumValue = floatval($_POST['minimumValue']);

        if ($discountType === 'V' && $discountValue > $minimumValue) {
            http_response_code(422);
            echo json_encode([
                'error' => 'O valor do Desconto não pode ser maior que o Valor Mínimo da Compra.'
            ]);
            return;
        }

        $c = new Coupon();
        $c->setCode($_POST['code'])
            ->setDescription($_POST['description'])
            ->setActive($_POST['active'])
            ->setDiscountType($discountType)
            ->setDiscountValue($discountValue)
            ->setExpirationDate($_POST['expirationDate'])
            ->setUsageLimit($_POST['usageLimit'])
            ->setMinimumValue($minimumValue);

        echo json_encode($c->store());
    }

    public function listing()
    {
        $c = new Coupon();
        $data = $c->listing();

        foreach ($data as $k => $v) {
            $id = $v['id'];
            $data[$k]['actions'] = '
            <a href="/coupon/show/' . $id . '" class="btn btn-sm btn-secondary" title="Visualizar Cupom">
                <i class="bi bi-eye"></i>
            </a>
            <a href="/coupon/edit/' . $id . '" class="btn btn-sm btn-primary" title="Editar Cupom">
                <i class="bi bi-pencil"></i>
            </a>
            <button class="btn btn-sm btn-danger btn-delete" data-id="' . $id . '" title="Excluir Cupom">
                <i class="bi bi-trash"></i>
            </button>
        ';
        }

        header('Content-Type: application/json');
        echo json_encode([
            "recordsFiltered" => count($data),
            "recordsTotal" => count($data),
            "data" => $data,
        ]);
    }

    public function select($id)
    {
        $c = new Coupon();
        $c->setId($id);

        $data = $c->select();

        if (isset($data[0])) {
            header('Content-Type: application/json');
            echo json_encode($data[0]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cupom não encontrado']);
        }
    }

    public function update()
    {
        $discountType = $_POST['discountType'];
        $discountValue = floatval($_POST['discountValue']);
        $minimumValue = floatval($_POST['minimumValue']);

        if ($discountType === 'V' && $discountValue > $minimumValue) {
            http_response_code(422);
            echo json_encode([
                'error' => 'O valor do Desconto não pode ser maior que o Valor Mínimo da Compra.'
            ]);
            return;
        }

        $c = new Coupon();
        $c->setId($_POST['id'])
            ->setCode($_POST['code'])
            ->setDescription($_POST['description'])
            ->setActive($_POST['active'])
            ->setDiscountType($_POST['discountType'])
            ->setDiscountValue($_POST['discountValue'])
            ->setExpirationDate($_POST['expirationDate'])
            ->setUsageLimit($_POST['usageLimit'])
            ->setMinimumValue($_POST['minimumValue']);

        echo json_encode($c->update());
    }

    public function delete()
    {
        $c = new Coupon();
        $c->setId($_POST['id']);
        echo json_encode($c->delete());
    }


    public function check()
    {
        $subtotal = $_GET['subtotal'] ?? 0;
        $couponCode = $_GET['couponCode'] ?? '';

        if (empty($subtotal)) {
            http_response_code(400);
            echo json_encode(['error' => 'Subtotal não informado']);
            return;
        }

        if (empty($couponCode)) {
            http_response_code(400);
            echo json_encode(['error' => 'Código do cupom não informado']);
            return;
        }

        $c = new \App\Models\Coupon();

        $coupon = $c->getCouponByCode($couponCode);

        if (empty($coupon)) {
            http_response_code(404);
            echo json_encode(['error' => 'Cupom inválido ou inexistente']);
            return;
        }

        if ($coupon['exceededLimit']) {
            http_response_code(400);
            echo json_encode(['error' => 'Este cupom atingiu o limite de uso']);
            return;
        }

        if ($subtotal < $coupon['minimumValue']) {
            http_response_code(400);
            echo json_encode(['error' => 'Subtotal abaixo do valor mínimo para o cupom']);
            return;
        }

        $discountValue = $this->calculateDiscount($coupon, $subtotal);
        $finalSubtotal = $subtotal - $discountValue;

        header('Content-Type: application/json');

        echo json_encode([
            'coupon' => $coupon,
            'discount' => $discountValue,
            'finalSubtotal' => $finalSubtotal
        ]);
    }


    private function calculateDiscount($coupon, $subtotal)
    {
        if ($coupon['discountType'] === 'P') {
            return ($coupon['discountValue'] / 100) * $subtotal;
        } else {
            return $coupon['discountValue'];
        }
    }

    
}
