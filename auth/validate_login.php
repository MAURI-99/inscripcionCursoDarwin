<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Guardar el email para volverlo a mostrar en el formulario si hay error
    $_SESSION['input_email'] = $email;

    $stmt = $conn->prepare("SELECT id, full_name, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login exitoso
            $_SESSION['email'] = $email;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['student_id'] = $id;

            // Limpiar datos temporales
            unset($_SESSION['login_error'], $_SESSION['error_type'], $_SESSION['input_email']);

            header("Location: ../views/dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta.";
            $_SESSION['error_type'] = 'password';
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Correo electrónico no registrado.";
        $_SESSION['error_type'] = 'email';
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}



