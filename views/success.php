<?php 
session_start();
include '../includes/header.php'; 

// Opcional: podrías verificar que el usuario esté realmente logueado o que venga de un registro válido
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>

<div class="text-center my-5">
    <h2 class="text-success mb-4">¡Registro exitoso!</h2>
    <p class="lead">Gracias por inscribirte en nuestro curso. Ahora puedes acceder a tu panel de control para ver más detalles.</p>
    <a href="dashboard.php" class="btn btn-primary btn-lg mt-3">Ir al Panel de Control</a>
</div>

<?php include '../includes/footer.php'; ?>




