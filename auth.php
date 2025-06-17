<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
// Penjelasan: Backend autentikasi user Costonomy
function register($email, $password, $name) {
    global $pdo;
    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Email sudah terdaftar'];
        }
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);
        // Ambil user_id yang baru saja dibuat
        $userId = $pdo->lastInsertId();
        // Tambahkan kategori default ke tabel budgets
        $defaultCategories = [
            ['Tempat Tinggal', 0],
            ['Makanan', 0],
            ['Transportasi', 0],
            ['Hiburan', 0],
            ['Utilitas', 0],
            ['Kesehatan', 0]
        ];
        $stmtBudget = $pdo->prepare("INSERT INTO budgets (user_id, category, amount, enabled) VALUES (?, ?, ?, 1)");
        foreach ($defaultCategories as $cat) {
            $stmtBudget->execute([$userId, $cat[0], $cat[1]]);
        }
        return ['success' => true, 'message' => 'Registrasi berhasil'];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}
function login($email, $password) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            return ['success' => true, 'message' => 'Login berhasil'];
        }
        return ['success' => false, 'message' => 'Email atau password salah'];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}
function logout() {
    session_destroy();
    return ['success' => true, 'message' => 'Logout berhasil'];
}
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
// Handle AJAX requests
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = ['success' => false, 'message' => 'Invalid action'];
    switch ($action) {
        case 'register':
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $response = register($email, $password, $name);
            break;
        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $response = login($email, $password);
            break;
        case 'logout':
            $response = logout();
            break;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?> 