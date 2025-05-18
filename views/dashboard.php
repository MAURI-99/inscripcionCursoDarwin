<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['student_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

$student_id = $_SESSION['student_id'];

$sql = "SELECT cr.registration_date, c.title, c.category, c.duration, c.start_date, c.end_date, c.instructor
        FROM course_registrations cr
        JOIN courses c ON cr.course_id = c.id
        WHERE cr.student_id = ?
        ORDER BY cr.registration_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h2 class="text-primary fw-bold">Panel de Control</h2>
    <a href="../auth/logout.php" class="btn btn-outline-danger fw-semibold shadow-sm">Cerrar Sesión</a>
</div>

<p class="lead">Bienvenido, <strong class="text-success"><?= htmlspecialchars($_SESSION['full_name']) ?></strong></p>

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
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['duration']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['start_date']))) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['end_date']))) ?></td>
                        <td><?= htmlspecialchars($row['instructor']) ?></td>
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
                <h5 class="mb-0 fw-bold"><?= htmlspecialchars($row['title']) ?></h5>
            </div>
            <div class="p-3">
                <p class="mb-2"><strong>Categoría:</strong> <?= htmlspecialchars($row['category']) ?></p>
                <p class="mb-2"><strong>Duración:</strong> <?= htmlspecialchars($row['duration']) ?></p>
                <p class="mb-2"><strong>Instructor:</strong> <?= htmlspecialchars($row['instructor']) ?></p>
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

<?php include '../includes/footer.php'; ?>



