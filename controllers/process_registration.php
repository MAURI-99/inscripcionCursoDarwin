<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $address = trim($_POST['address']);
    $education = trim($_POST['education_level']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Aquí cambiamos: recibimos un array de cursos
    $courses = $_POST['courses'] ?? [];

    // Guardar valores antiguos para reutilizar en formulario, incluyendo cursos
    $_SESSION['old'] = [
        'full_name' => $full_name,
        'email' => $email,
        'birthdate' => $birthdate,
        'gender' => $gender,
        'address' => $address,
        'education_level' => $education,
        'courses' => $courses
    ];

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

    if (empty($courses)) {
        $_SESSION['error'] = "Debes seleccionar al menos un curso.";
        header("Location: ../views/register.php");
        exit;
    }

    // Validar si el email ya existe
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

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO students (full_name, email, birthdate, gender, address, education_level, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $email, $birthdate, $gender, $address, $education, $hashed_password);

    if ($stmt->execute()) {
        $student_id = $stmt->insert_id;

        // Sanitizar IDs de cursos (por si acaso)
        $courses = array_map('intval', $courses);

        $stmt2 = $conn->prepare("INSERT INTO course_registrations (student_id, course_id) VALUES (?, ?)");
        foreach ($courses as $course_id) {
            $stmt2->bind_param("ii", $student_id, $course_id);
            $stmt2->execute();
        }
        $stmt2->close();

        // Registro exitoso: limpiar datos antiguos y guardar sesión usuario
        unset($_SESSION['old']);
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['student_id'] = $student_id;

        header("Location: ../views/dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Error al registrar. Intente de nuevo.";
        header("Location: ../views/register.php");
        exit;
    }
} else {
    header("Location: ../views/register.php");
    exit;
}




