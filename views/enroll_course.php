<?php
session_start();
require_once '../includes/db.php'; // Ajusta la ruta si está en otra ubicación

// Verifica si el estudiante está logueado
if (!isset($_SESSION['student_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Validación: al menos un curso seleccionado
if (!isset($_POST['courses']) || !is_array($_POST['courses']) || count($_POST['courses']) === 0) {
    $_SESSION['error'] = "Debes seleccionar al menos un curso para inscribirte.";
    header("Location: ../views/dashboard.php");
    exit();
}

$courses = $_POST['courses'];

// Preparar la inserción
$stmt = $conn->prepare("INSERT IGNORE INTO course_registrations (student_id, course_id, registration_date) VALUES (?, ?, NOW())");

if (!$stmt) {
    $_SESSION['error'] = "Error al preparar la inscripción: " . $conn->error;
    header("Location: ../views/dashboard.php");
    exit();
}

$inserted = 0;
foreach ($courses as $course_id) {
    $course_id = intval($course_id); // Sanitiza
    $stmt->bind_param("ii", $student_id, $course_id);
    if ($stmt->execute()) {
        $inserted++;
    }
}

$stmt->close();
$conn->close();

if ($inserted > 0) {
    $_SESSION['success'] = "Te has inscrito exitosamente en $inserted curso(s).";
} else {
    $_SESSION['error'] = "No se realizaron nuevas inscripciones. Puede que ya estés inscrito en esos cursos.";
}

header("Location: ../views/dashboard.php");
exit();
