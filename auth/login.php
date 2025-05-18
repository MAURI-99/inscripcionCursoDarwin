<?php 
session_start();

if (isset($_SESSION['email'])) {
    header("Location: ../views/dashboard.php");
    exit;
}

include '../includes/header.php';

$error = $_SESSION['error'] ?? '';
$error_type = $_SESSION['error_type'] ?? '';
$input_email = $_SESSION['input_email'] ?? '';

unset($_SESSION['error'], $_SESSION['error_type'], $_SESSION['input_email']);
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-success text-white rounded-top-4">
                <h3 class="mb-0 fw-bold text-center">Iniciar Sesión</h3>
            </div>
            <div class="card-body px-4 py-5">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <form action="validate_login.php" method="POST" novalidate>
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-uppercase">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-control-lg"
                               id="email" value="<?= htmlspecialchars($input_email) ?>" required autofocus autocomplete="email"
                               placeholder="usuario@ejemplo.com" />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-uppercase">Contraseña</label>
                        <div class="input-group input-group-lg">
                            <input type="password" name="password" class="form-control" id="password" required autocomplete="current-password" placeholder="Tu contraseña" />
                            <button class="btn btn-outline-success" type="button" id="togglePassword" aria-label="Mostrar/ocultar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" style="letter-spacing: 1px;">Ingresar</button>
                </form>

                <?php if ($error_type === 'email'): ?>
                    <div class="mt-4 text-center">
                        <a href="forgot_username.php" class="text-success fw-semibold text-decoration-none">¿Olvidaste tu usuario?</a>
                    </div>
                <?php elseif ($error_type === 'password'): ?>
                    <div class="mt-4 text-center">
                        <a href="forgot_password.php" class="text-success fw-semibold text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>

<?php include '../includes/footer.php'; ?>








