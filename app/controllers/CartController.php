<?php

namespace App\Controllers;

use Core\Controller;

class CartController extends Controller
{
    public function add()
    {
        session_start();

        $productId = $_POST['productId'];
        $variationId = $_POST['variationId'] ?? null;

        $quantity = (int) $_POST['quantity'];

        $price = is_numeric($_POST['price']) ? (float) $_POST['price'] : null;

        $key = $variationId ? "$productId-$variationId" : $productId;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$key] = [
                'productId'   => $productId,
                'variationId' => $variationId,
                'quantity'    => $quantity,
                'price'       => $price
            ];
        }

        echo json_encode(['success' => true, 'count' => $this->getTotalItemCount()]);
    }

    public function list()
    {
        session_start();
        $cart = $_SESSION['cart'] ?? [];

        $formatted = array_map(function ($item) {
            return [
                'productId'   => $item['productId'],
                'variationId' => $item['variationId'],
                'quantity'    => (int) $item['quantity'],
                'price'       => is_numeric($item['price']) ? (float) $item['price'] : 0.0
            ];
        }, array_values($cart));

        header('Content-Type: application/json');
        echo json_encode($formatted);
    }

    public function remove()
    {
        session_start();
        $productId = $_POST['productId'];
        $variationId = $_POST['variationId'];

        $key = $variationId ? "$productId-$variationId" : $productId;

        unset($_SESSION['cart'][$key]);

        echo json_encode(['success' => true, 'count' => $this->getTotalItemCount()]);
    }

    public function count()
    {
        session_start();
        echo json_encode(['count' => $this->getTotalItemCount()]);
    }

    private function getTotalItemCount()
    {
        $cart = $_SESSION['cart'] ?? [];
        $totalItems = 0;

        foreach ($cart as $item) {
            $totalItems += (int) $item['quantity'];
        }

        return $totalItems;
    }

    
}
