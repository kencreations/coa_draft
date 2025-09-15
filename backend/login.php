<?php
session_start();
header('Content-Type: application/json');
include 'connection.php';

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email)) {
    echo json_encode(["success" => false, "message" => "Email is required."]);
    exit;
}
if (empty($password)) {
    echo json_encode(["success" => false, "message" => "Password is required."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Email is not registered."]);
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Incorrect password."]);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

$post_last_login = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
$post_last_login->execute([$user['id']]);

$redirect = match($user['role']) {
    '0' => './admin/dashboard.php',
    '1' => './vet/dashboard.php',
    default => './dashboard.php',
};

echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "redirect" => $redirect
]);
?>