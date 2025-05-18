<?php  
session_start();
if (isset($_SESSION['email'])) {
    header("Location: ../views/dashboard.php");
    exit;
}

include '../includes/header.php'; 
include '../includes/db.php';

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

$result = $conn->query("SELECT * FROM courses ORDER BY title ASC");
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4>Formulario de Inscripción</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form id="registrationForm" action="../controllers/process_registration.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nombre Completo</label>
                        <input 
                            type="text" name="full_name" id="full_name" class="form-control" required 
                            value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" 
                            autofocus autocomplete="name" 
                            pattern=".{3,}" 
                            title="El nombre debe tener al menos 3 caracteres"
                        />
                        <div class="invalid-feedback">Por favor ingresa un nombre válido (mínimo 3 caracteres).</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input 
                            type="email" name="email" id="email" class="form-control" required 
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>" 
                            autocomplete="email" 
                        />
                        <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                    </div>
                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Fecha de Nacimiento</label>
                        <input 
                            type="date" name="birthdate" id="birthdate" class="form-control" required 
                            value="<?= htmlspecialchars($old['birthdate'] ?? '') ?>" 
                            max="<?= date('Y-m-d', strtotime('-18 years')) ?>" 
                            title="Debes ser mayor de 18 años"
                        />
                        <div class="invalid-feedback">Debes ser mayor de 18 años para inscribirte.</div>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Género</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino" <?= (isset($old['gender']) && $old['gender'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= (isset($old['gender']) && $old['gender'] === 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= (isset($old['gender']) && $old['gender'] === 'Otro') ? 'selected' : '' ?>>Otro</option>
                        </select>
                        <div class="invalid-feedback">Por favor selecciona tu género.</div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input 
                            type="text" name="address" id="address" class="form-control" required 
                            value="<?= htmlspecialchars($old['address'] ?? '') ?>" autocomplete="street-address" 
                            pattern=".{5,}" 
                            title="La dirección debe tener al menos 5 caracteres"
                        />
                        <div class="invalid-feedback">Por favor ingresa una dirección válida (mínimo 5 caracteres).</div>
                    </div>
                    <div class="mb-3">
                        <label for="education_level" class="form-label">Nivel Educativo</label>
                        <input 
                            type="text" name="education_level" id="education_level" class="form-control" required 
                            value="<?= htmlspecialchars($old['education_level'] ?? '') ?>"
                            pattern=".{3,}" 
                            title="Debe ingresar un nivel educativo válido"
                        />
                        <div class="invalid-feedback">Por favor ingresa un nivel educativo válido.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Selecciona los cursos</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="coursesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Seleccionar cursos
                            </button>
                            <ul class="dropdown-menu p-3" aria-labelledby="coursesDropdown" style="max-height: 300px; overflow-y: auto;">
                                <?php
                                $coursesSelected = $old['courses'] ?? [];
                                while ($row = $result->fetch_assoc()) {
                                    $checked = in_array($row['id'], $coursesSelected) ? 'checked' : '';
                                    echo "<li class='form-check'>";
                                    echo "<input class='form-check-input' type='checkbox' name='courses[]' value='{$row['id']}' id='course_{$row['id']}' $checked>";
                                    echo "<label class='form-check-label' for='course_{$row['id']}'>{$row['title']}</label>";
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <small class="form-text text-muted">Selecciona uno o más cursos</small>
                        <div id="courseError" class="text-danger d-none mt-1">Por favor selecciona al menos un curso.</div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input 
                                type="password" name="password" class="form-control" required 
                                autocomplete="new-password"
                                id="password"
                                aria-describedby="passwordHelp"
                            />
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="passwordHelp" class="form-text">
                            La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.
                        </div>
                        <div class="invalid-feedback" id="passwordError">Contraseña inválida.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                        <input 
                            type="password" name="confirm_password" class="form-control" required 
                            autocomplete="new-password"
                            id="confirm_password"
                        />
                        <div id="confirmPasswordError" class="text-danger d-none mt-1">Las contraseñas no coinciden.</div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Registrar e Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const form = document.getElementById('registrationForm');
    const courseError = document.getElementById('courseError');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const togglePasswordBtn = document.getElementById('togglePassword');

    togglePasswordBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordBtn.querySelector('i').classList.replace('bi-eye', 'bi-eye-slash');
            togglePasswordBtn.setAttribute('aria-label', 'Ocultar contraseña');
        } else {
            passwordInput.type = 'password';
            togglePasswordBtn.querySelector('i').classList.replace('bi-eye-slash', 'bi-eye');
            togglePasswordBtn.setAttribute('aria-label', 'Mostrar contraseña');
        }
    });

    form.addEventListener('submit', (e) => {
        let valid = true;

        // Validar cursos seleccionados
        const courses = form.querySelectorAll('input[name="courses[]"]');
        let oneChecked = false;
        courses.forEach(c => { if (c.checked) oneChecked = true; });
        if (!oneChecked) {
            courseError.classList.remove('d-none');
            valid = false;
        } else {
            courseError.classList.add('d-none');
        }

        // Validar contraseña con regex
        const passwordVal = passwordInput.value.trim();
        const confirmVal = confirmPasswordInput.value.trim();
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (!passwordRegex.test(passwordVal)) {
            passwordInput.classList.add('is-invalid');
            passwordError.style.display = 'block';
            valid = false;
        } else {
            passwordInput.classList.remove('is-invalid');
            passwordError.style.display = 'none';
            passwordInput.classList.add('is-valid');
        }

        // Validar confirmación contraseña
        if (passwordVal !== confirmVal || confirmVal === '') {
            confirmPasswordError.classList.remove('d-none');
            confirmPasswordInput.classList.add('is-invalid');
            valid = false;
        } else {
            confirmPasswordError.classList.add('d-none');
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        }

        // Validación HTML5 nativa para otros campos
        if (!form.checkValidity()) {
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            e.stopPropagation();
        }

        form.classList.add('was-validated');
    });
})();
</script>

<?php include '../includes/footer.php'; ?>








