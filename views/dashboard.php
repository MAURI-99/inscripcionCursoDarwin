<?php
session_start();
if (!isset($_SESSION['email'], $_SESSION['student_id'], $_SESSION['full_name'])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';
$student_id = $_SESSION['student_id'];
// Preparar la consulta con manejo de errores
$sql = "SELECT cr.registration_date, c.title, c.category, c.duration, c.start_date, c.end_date, c.instructor
        FROM course_registrations cr
        JOIN courses c ON cr.course_id = c.id
        WHERE cr.student_id = ?
        ORDER BY cr.registration_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h2 class="text-primary fw-bold">Panel de Control</h2>
    <a href="../auth/logout.php" class="btn btn-outline-danger fw-semibold shadow-sm">Cerrar Sesión</a>
</div>

<p class="lead">Bienvenido, <strong class="text-success"><?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8') ?></strong></p>

<h4 class="mt-4 mb-3 text-success fw-semibold">Tus Inscripciones</h4>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle">
            <thead class="table-success text-success">
                <tr>
                    <th>Curso</th>
                    <th>Categoría</th>
                    <th>Duración</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Instructor</th>
                    <th>Fecha de Inscripción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['duration'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['start_date']))) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['end_date']))) ?></td>
                        <td><?= htmlspecialchars($row['instructor'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($row['registration_date']))) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tarjetas de Cursos -->
    <h4 class="mt-5 mb-3 text-primary fw-semibold">Resumen Visual de Cursos</h4>
    <div class="row">
        <?php
        $result->data_seek(0); // Reiniciar puntero de resultados

        // Definir una paleta de colores suaves
        $colorThemes = [
            ['bg' => '#e3f2fd', 'header' => '#2196f3'],
            ['bg' => '#e8f5e9', 'header' => '#4caf50'],
            ['bg' => '#fff8e1', 'header' => '#ffb300'],
            ['bg' => '#fce4ec', 'header' => '#ec407a'],
            ['bg' => '#ede7f6', 'header' => '#673ab7'],
            ['bg' => '#e0f2f1', 'header' => '#009688']
        ];

        while ($row = $result->fetch_assoc()):
            $theme = $colorThemes[array_rand($colorThemes)];
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="shadow-sm rounded-4" style="background-color: <?= $theme['bg'] ?>; border: 1px solid #ddd;">
                <div style="background-color: <?= $theme['header'] ?>; color: white;" class="rounded-top-4 px-3 py-2">
                    <h5 class="mb-0 fw-bold"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                </div>
                <div class="p-3">
                    <p class="mb-2"><strong>Categoría:</strong> <?= htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="mb-2"><strong>Duración:</strong> <?= htmlspecialchars($row['duration'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="mb-2"><strong>Instructor:</strong> <?= htmlspecialchars($row['instructor'], ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="d-flex justify-content-between">
                        <small><strong>Inicio:</strong> <?= date('d/m/Y', strtotime($row['start_date'])) ?></small>
                        <small><strong>Fin:</strong> <?= date('d/m/Y', strtotime($row['end_date'])) ?></small>
                    </div>
                </div>
                <div class="px-3 py-2 border-top" style="background-color: rgba(0, 0, 0, 0.03); border-radius: 0 0 16px 16px;">
                    <small class="text-muted">Inscrito el <?= date('d/m/Y H:i', strtotime($row['registration_date'])) ?></small>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

<?php else: ?>
    <div class="alert alert-info mt-3 shadow-sm" role="alert">
        No te has inscrito en ningún curso todavía. <a href="register.php" class="alert-link">Explora nuestros cursos aquí.</a>
    </div>
<?php endif; ?>

<!-- Botón para abrir modal -->
<div class="mb-4">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
        Inscribirse en un nuevo curso
    </button>
</div>

<!-- Modal Inscripción Cursos -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="enrollForm" method="POST" action="enroll_course.php" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="enrollModalLabel">Inscribirse en un nuevo curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p>Selecciona uno o varios cursos disponibles para inscribirte:</p>
          <div class="mb-3">
            <?php
              $sqlCourses = "SELECT id, title, category, start_date, end_date FROM courses
                WHERE id NOT IN (
                  SELECT course_id FROM course_registrations WHERE student_id = ?
                )
                ORDER BY title ASC";
              $stmtCourses = $conn->prepare($sqlCourses);
              if (!$stmtCourses) {
                  echo "<p class='text-danger'>Error al cargar los cursos disponibles.</p>";
              } else {
                  $stmtCourses->bind_param("i", $student_id);
                  $stmtCourses->execute();
                  $resultCourses = $stmtCourses->get_result();

                  if ($resultCourses->num_rows > 0):
                      while ($course = $resultCourses->fetch_assoc()):
            ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="courses[]" value="<?= htmlspecialchars($course['id'], ENT_QUOTES, 'UTF-8') ?>" id="course<?= htmlspecialchars($course['id'], ENT_QUOTES, 'UTF-8') ?>">
                <label class="form-check-label" for="course<?= htmlspecialchars($course['id'], ENT_QUOTES, 'UTF-8') ?>">
                  <strong><?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($course['category'], ENT_QUOTES, 'UTF-8') ?>) - 
                  <?= date('d/m/Y', strtotime($course['start_date'])) ?> a <?= date('d/m/Y', strtotime($course['end_date'])) ?>
                </label>
              </div>
            <?php
                      endwhile;
                  else:
                    echo "<p class='text-muted'>No hay cursos disponibles para inscripción.</p>";
                  endif;
              }
            ?>
          </div>
          <div class="invalid-feedback d-block" id="coursesError" style="display:none;">
            Por favor selecciona al menos un curso.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Inscribirme</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Validación simple para que al menos un checkbox esté marcado antes de enviar el formulario
  document.getElementById('enrollForm').addEventListener('submit', function(event) {
    const checkboxes = document.querySelectorAll('input[name="courses[]"]:checked');
    const errorDiv = document.getElementById('coursesError');
    if (checkboxes.length === 0) {
      event.preventDefault();
      errorDiv.style.display = 'block';
    } else {
      errorDiv.style.display = 'none';
    }
  });
</script>

<?php include '../includes/footer.php'; ?>





