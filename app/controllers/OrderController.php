<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Stock;
use App\Models\Product;
use App\Controllers\OrderMailerController;

class OrderController extends Controller
{
    public function index()
    {
        $this->view('order/index');
    }

    public function show()
    {
        $this->view('order/show');
    }

    public function store()
    {
        session_start();

        if (empty($_SESSION['cart'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Carrinho vazio']);
            return;
        }

        $clientName = $_POST['clientName'];
        $email      = $_POST['email'];
        $cep        = $_POST['cep'];
        $address    = $_POST['address'];
        $city       = $_POST['city'];
        $state      = $_POST['state'];
        $couponCode = $_POST['couponCode'] ?? null;

        $cart = $_SESSION['cart'];

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = 0;
        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            $shipping = 15.00;
        } else if ($subtotal > 200.00) {
            $shipping = 0.00;
        } else {
            $shipping = 20.00;
        }

        $discount = 0;

        if ($couponCode) {
            $coupon = new Coupon();
            $validCoupon = $coupon->getCouponByCode($couponCode);

            if ($validCoupon) {
                if ($validCoupon['exceededLimit']) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Este cupom atingiu o limite de uso']);
                    return;
                }

                if ($subtotal < $validCoupon['minimumValue']) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Subtotal abaixo do valor mínimo para o cupom']);
                    return;
                }

                if ($validCoupon['discountType'] === 'P') {
                    $discount = ($validCoupon['discountValue'] / 100) * $subtotal;
                } else {
                    $discount = $validCoupon['discountValue'];
                }

                $couponId = $validCoupon['id'];
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Cupom inválido']);
                return;
            }
        }

        $total = max(0, $subtotal + $shipping - $discount);

        $o = new Order();
        $o->setClientName($clientName)
            ->setEmail($email)
            ->setCep($cep)
            ->setAddress($address)
            ->setCity($city)
            ->setState($state)
            ->setSubtotal($subtotal)
            ->setShipping($shipping)
            ->setDiscount($discount)
            ->setTotal($total)
            ->setCouponId($couponId ?? null)
            ->setStatus('P');

        foreach ($cart as $item) {
            $o->addItem(
                $item['productId'],
                $item['variationId'],
                $item['quantity'],
                $item['price']
            );
        }

        $orderId = $o->store();

        if ($orderId) {
            foreach ($cart as $item) {
                $s = new \App\Models\Stock();
                $s->setProductId($item['productId'])
                    ->setVariationId($item['variationId'])
                    ->setAmount(-$item['quantity']);
                $s->updatedAmount();
            }

            $orderItems = [];
            foreach ($cart as $item) {
                if (isset($item['productId'])) {
                    $product = new Product();
                    $product->setId($item['productId']);
                    $productData = $product->select();
                    $productName = $productData[0]['name'] ?? 'Produto desconhecido';
                } else {
                    $productName = 'Produto desconhecido';
                }

                $orderItems[] = [
                    'productName' => $productName,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }

            $mailer = new OrderMailerController();
            $emailSent = $mailer->sendEmailCreateOrder($email, $clientName, $orderId, $orderItems, $total);

            if ($emailSent) {
                echo json_encode(['success' => true, 'orderId' => $orderId, 'emailSent' => true]);
            } else {
                echo json_encode(['success' => true, 'orderId' => $orderId, 'emailSent' => false]);
            }

            unset($_SESSION['cart']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao salvar o pedido']);
        }
    }


    public function listing()
    {
        $o = new Order();
        $data = $o->listing();

        foreach ($data as $k => $v) {
            $id = $v['id'];
            $status = $v['status'];
            $actions = '
            <a href="/order/show/' . $id . '" class="btn btn-sm btn-secondary" title="Visualizar Pedido">
                <i class="bi bi-eye"></i>
            </a>
        ';

            if ($status === 'P') {
                $actions .= '
                <button class="btn btn-sm btn-success btn-deliver" data-id="' . $id . '" title="Marcar como Entregue">
                    <i class="bi bi-check-circle"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-cancel" data-id="' . $id . '" title="Cancelar Pedido">
                    <i class="bi bi-x-circle"></i>
                </button>
            ';
            }

            $data[$k]['actions'] = $actions;
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
        $o = new Order();
        $o->setId($id);

        $data = $o->select();

        if (isset($data[0])) {
            header('Content-Type: application/json');
            echo json_encode($data[0]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
        }
    }

    public function delivered()
    {
        $o = new Order();
        $o->setId($_POST['id']);

        $data = $o->select();
        if (!isset($data[0])) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
            return;
        }

        $clientEmail = $data[0]['email'];
        $clientName  = $data[0]['clientName'];
        $orderId     = $data[0]['id'];

        $success = $o->delivered();

        if ($success) {
            $mailer = new OrderMailerController();
            $mailer->sendEmailDeliveredOrder($clientEmail, $clientName, $orderId);

            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao cancelar pedido']);
        }
    }

    public function cancel()
    {
        $o = new Order();
        $o->setId($_POST['id']);

        $data = $o->select();
        if (!isset($data[0])) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
            return;
        }

        $clientEmail = $data[0]['email'];
        $clientName  = $data[0]['clientName'];
        $orderId     = $data[0]['id'];

        $success = $o->cancel();

        if ($success) {
            $mailer = new OrderMailerController();
            $mailer->sendEmailCancelOrder($clientEmail, $clientName, $orderId);

            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Erro ao cancelar pedido']);
        }
    }
}
