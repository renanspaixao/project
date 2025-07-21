<?php $current = $_SERVER['REQUEST_URI']; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Montlink' ?></title>
  <link rel="icon" type="image/png" href="https://images.montink.tw/perfil_montink/16530043316286d82b55cf6.png?width=150&height=50">
  <link rel="stylesheet" href="/public/assets/styles/global.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: row;
    }

    .sidebar {
      width: 10%;
      min-height: 100vh;
      background-color: #212529;
    }

    .main-content {
      width: 90%;
      display: flex;
      flex-direction: column;
    }

    .content-wrapper {
      flex: 1;
    }

    .navbar {
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
      min-height: 56px;
    }

    .sidebar .logo-area {
      height: 56px;
    }

    .align-navbar-hr {
      margin-top: 56px;
    }

    .nav-link.active {
      background-color: #53b6b2 !important;
      color: #fff !important;
    }

    .company {
      height: 98px;
    }

    .navHeight {
      max-height: 101px;
      flex-grow: 1;
    }

    hr {
      border-top: 1px solid #ccc !important;
      opacity: 1 !important;
      width: 100%;
      margin: 0.75rem 0;
    }
  </style>
</head>

<body>
  <div class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar">
    <div class="company d-flex align-items-center justify-content-center">
      <a href="/" class="d-flex align-items-center justify-content-center logo-area text-white text-decoration-none">
        <span class="fs-4 fw-semibold">Montlink</span>
      </a>
    </div>
    <hr class="border-light my-1">
    <?php $current = $_SERVER['REQUEST_URI']; ?>
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
        <a href="/coupon" class="nav-link text-white <?= strpos($current, '/coupon') === 0 ? 'active' : '' ?>">
          Cupons
        </a>
      </li>
      <li>
        <a href="/stock" class="nav-link text-white <?= strpos($current, '/stock') === 0 ? 'active' : '' ?>">
          Estoque
        </a>
      </li>
      <li>
        <a href="/product" class="nav-link text-white <?= strpos($current, '/product') === 0 ? 'active' : '' ?>">
          Produtos
        </a>
      </li>
      <li>
        <a href="/order" class="nav-link text-white <?= strpos($current, '/order') === 0 ? 'active' : '' ?>">
          Pedidos
        </a>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid navHeight">
        <a class="navbar-brand d-flex align-items-center" href="/">
          <img src="https://images.montink.tw/perfil_montink/16530043316286d82b55cf6.png?width=150&height=50" alt="Logo" width="93px" height="auto" class="me-2">
        </a>
      </div>
    </nav>
    <div class="content-wrapper">
      <div class="container-fluid p-0 m-0">