<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger cursos seleccionados (array)
    $courses = $_POST['courses'] ?? [];

    // Validar que haya al menos un curso seleccionado
    if (empty($courses)) {
        $_SESSION['error'] = "Debes seleccionar al menos un curso.";
        header("Location: ../views/register.php");
        exit;
    }

    // Si usuario ya autenticado: añadir cursos a su cuenta
    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];

        // Convertir a enteros para seguridad
        $courses = array_map('intval', $courses);

        // Obtener cursos ya inscritos para no duplicar
        $stmtCheck = $conn->prepare("SELECT course_id FROM course_registrations WHERE student_id = ?");
        $stmtCheck->bind_param("i", $student_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        $already_registered_courses = [];
        while ($row = $resultCheck->fetch_assoc()) {
            $already_registered_courses[] = (int)$row['course_id'];
        }
        $stmtCheck->close();

        // Insertar solo cursos no inscritos aún
        $stmtInsert = $conn->prepare("INSERT INTO course_registrations (student_id, course_id) VALUES (?, ?)");
        $addedCourses = 0;
        foreach ($courses as $course_id) {
            if (!in_array($course_id, $already_registered_courses)) {
                $stmtInsert->bind_param("ii", $student_id, $course_id);
                $stmtInsert->execute();
                $addedCourses++;
            }
        }
        $stmtInsert->close();

        if ($addedCourses > 0) {
            $_SESSION['success'] = "Se agregaron $addedCourses nuevos cursos a tu inscripción.";
        } else {
            $_SESSION['info'] = "Ya estás inscrito en esos cursos seleccionados.";
        }

        header("Location: ../auth/login.php");
        exit;

    } else {
        // Usuario NO autenticado: registrar usuario y sus cursos

        // Recoger y sanitizar inputs básicos
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $birthdate = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $address = trim($_POST['address']);
        $education = trim($_POST['education_level']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Guardar datos antiguos para repoblar formulario si hay error
        $_SESSION['old'] = [
            'full_name' => $full_name,
            'email' => $email,
            'birthdate' => $birthdate,
            'gender' => $gender,
            'address' => $address,
            'education_level' => $education,
            'courses' => $courses
        ];

        // Validaciones básicas
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Las contraseñas no coinciden.";
            header("Location: ../views/register.php");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Correo electrónico inválido.";
            header("Location: ../views/register.php");
            exit;
        }

        // Verificar si email ya existe
        $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $_SESSION['error'] = "El correo electrónico ya está registrado.";
            header("Location: ../views/register.php");
            exit;
        }
        $check->close();

        // Encriptar contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo estudiante
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, birthdate, gender, address, education_level, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $birthdate, $gender, $address, $education, $hashed_password);

if ($stmt->execute()) {
    $student_id = $stmt->insert_id;

    // Inscribir en cursos seleccionados
    $courses = array_map('intval', $courses);
    $stmt2 = $conn->prepare("INSERT INTO course_registrations (student_id, course_id) VALUES (?, ?)");
    foreach ($courses as $course_id) {
        $stmt2->bind_param("ii", $student_id, $course_id);
        $stmt2->execute();
    }
    $stmt2->close();

    // Limpiar datos antiguos
    unset($_SESSION['old']);

    // No iniciar sesión automáticamente aquí,
    // solo avisar y redirigir al login para que ingrese con su cuenta.
    $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
    header("Location: ../auth/login.php");
    exit;
        }
    }
} else {
    // Si no es método POST, redirigir a registro
    header("Location: ../views/register.php");
    exit;
}