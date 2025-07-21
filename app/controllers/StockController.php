<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Stock;

class StockController extends Controller
{

    public function index()
    {
        $this->view('stock/index');
    }

    public function store()
    {
        $s = new Stock();
        $s->setProductId($_POST['productId'])
            ->setVariationId($_POST['variationId'])
            ->setAmount($_POST['amount']);

        echo json_encode($s->store());
    }

   public function listing()
    {
        $s = new Stock();
        $data = $s->listing();

        header('Content-Type: application/json');
        echo json_encode([
            "recordsFiltered" => count($data),
            "recordsTotal" => count($data),
            "data" => $data,
        ]);
    }


}
