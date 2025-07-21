<?php

namespace Core;

class Controller {
    public function __construct() {
        require_once __DIR__ . '/../app/helpers/layout.php';
    }

    public function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $view . '.php';
    }

    public function model($model) {
        $modelClass = "App\\Models\\" . $model;
        return new $modelClass();
    }
}
