<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistema de Inscripción a Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
    <style>
      /* Navbar con degradado verde */
      .navbar-custom {
        background: linear-gradient(135deg, #28a745, #218838);
        box-shadow: 0 4px 12px rgba(33, 136, 56, 0.6);
      }
      .navbar-custom .navbar-brand,
      .navbar-custom .nav-link {
        color: #e6f5e9;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        transition: color 0.3s ease;
      }
      .navbar-custom .nav-link:hover,
      .navbar-custom .nav-link:focus {
        color: #c7e3c9;
      }
      .navbar-custom .navbar-brand:hover {
        color: #d0f0c0;
      }
      /* Botón toggler personalizado */
      .navbar-custom .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.3);
      }
      .navbar-custom .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' " +
        "xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 0.9%29' " +
        "stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="../views/index.php">
      <i class="bi bi-journal-code me-2 fs-3"></i> Cursos Online
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav fw-semibold fs-5">
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="../views/index.php">
            <i class="bi bi-house-door-fill me-1"></i> Inicio
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="../views/register.php">
            <i class="bi bi-pencil-square me-1"></i> Inscribirse
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center" href="../auth/login.php">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">






