<?php
function layout($file, $data = []) {
    extract($data);
    require_once __DIR__ . '/../views/layouts/' . $file . '.php';
}
