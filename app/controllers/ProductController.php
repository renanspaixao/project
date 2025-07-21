<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $this->view('product/index');
    }

    public function create()
    {
        $this->view('product/create');
    }

    public function show()
    {
        $this->view('product/show');
    }

    public function edit()
    {
        $this->view('product/edit');
    }

    public function store()
    {
        $p = new Product();

        $price = isset($_POST['price']) && is_numeric($_POST['price']) ? (float)$_POST['price'] : 0;
        $p->setName($_POST['name'])
            ->setPrice($price);

        if (isset($_POST['variations']) && is_array($_POST['variations'])) {
            $sanitizedVariations = [];

            foreach ($_POST['variations'] as $var) {
                $sanitizedVariations[] = [
                    'color' => $var['color'] ?? null,
                    'size' => $var['size'] ?? null,
                    'price' => isset($var['price']) && is_numeric($var['price']) ? (float)$var['price'] : null,
                    'stock' => isset($var['stock']) && is_numeric($var['stock']) ? (int)$var['stock'] : null,
                ];
            }

            $p->setVariations($sanitizedVariations);
        }

        echo json_encode($p->store());
    }

    public function update()
    {
        $p = new Product();

        $price = isset($_POST['price']) && is_numeric($_POST['price']) ? (float)$_POST['price'] : 0;
        $p->setId($_POST['id'])
            ->setName($_POST['name'])
            ->setPrice($price);

        if (isset($_POST['variations']) && is_array($_POST['variations'])) {
            $sanitizedVariations = [];

            foreach ($_POST['variations'] as $var) {
                $sanitizedVariations[] = [
                    'color' => $var['color'] ?? null,
                    'size' => $var['size'] ?? null,
                    'price' => isset($var['price']) && is_numeric($var['price']) ? (float)$var['price'] : null,
                    'stock' => isset($var['stock']) && is_numeric($var['stock']) ? (int)$var['stock'] : null,
                ];
            }

            $p->setVariations($sanitizedVariations);
        }

        echo json_encode($p->update());
    }

    public function select($id)
    {
        $p = new Product();
        $p->setId($id);

        $data = $p->select();

        if (isset($data[0])) {
            header('Content-Type: application/json');
            echo json_encode($data[0]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
        }
    }

    public function listing()
    {
        $p = new Product();
        $data = $p->listing();

        foreach ($data as $k => $v) {
            $id = $v['id'];
            $data[$k]['actions'] = '
                <a href="/product/show/' . $id . '" class="btn btn-sm btn-secondary" title="Visualizar Produto">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="/product/edit/' . $id . '" class="btn btn-sm btn-primary" title="Editar Produto">
                    <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-danger btn-delete" data-id="' . $id . '" title="Excluir Produto">
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

    public function delete()
    {
        $p = new Product();
        $p->setId($_POST['id']);
        echo json_encode($p->delete());
    }

    public function variations($productId)
    {
        $p = new Product();
        $data = $p->getVariationsByProductId($productId);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
