<?php
session_start();

// Redirigir solo si el usuario ya inició sesión
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit;
}

include '../includes/header.php';
?>

<section class="hero-section d-flex align-items-center justify-content-center text-center" style="min-height: 70vh; background: linear-gradient(135deg, #e3f2df 0%, #f5faf7 100%);">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4" style="color: #218838; text-shadow: 1px 1px 4px rgba(33,136,56,0.3);">
            Bienvenido al Sistema de Inscripción a Cursos
        </h1>
        <p class="lead mb-5" style="color: #2e2e2e; font-weight: 600;">
            Regístrate en los cursos que te interesen y aprende con nosotros.
        </p>
        <a href="register.php" class="btn btn-success btn-lg mx-3 px-4" style="font-weight: 700; letter-spacing: 1px; box-shadow: 0 6px 18px rgba(33,136,56,0.5); transition: all 0.3s ease;">
            Inscribirse Ahora
        </a>
        <a href="../auth/login.php" class="btn btn-outline-success btn-lg mx-3 px-4" style="font-weight: 600; letter-spacing: 1px; transition: all 0.3s ease;">
            Iniciar Sesión
        </a>
    </div>
</section>

<?php include '../includes/footer.php'; ?>







