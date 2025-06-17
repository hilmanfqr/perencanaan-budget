<?php
// File ini kosong pada versi sebelum penambahan database.

session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Simpan dan ambil budget dari database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    if (isset($_GET['income'])) {
        // Simpan pemasukan bulanan
        $data = json_decode(file_get_contents('php://input'), true);
        $income = isset($data['monthlyIncome']) ? floatval($data['monthlyIncome']) : 0;
        $stmt = $pdo->prepare("UPDATE users SET monthly_income = ? WHERE id = ?");
        $stmt->execute([$income, $userId]);
        echo json_encode(['success' => true]);
        exit;
    }
    $budget = json_decode(file_get_contents('php://input'), true);
    // Hapus budget lama
    $stmt = $pdo->prepare("DELETE FROM budgets WHERE user_id = ?");
    $stmt->execute([$userId]);
    // Simpan budget baru
    $stmt = $pdo->prepare("INSERT INTO budgets (user_id, category, amount, enabled) VALUES (?, ?, ?, ?)");
    foreach ($budget as $item) {
        $stmt->execute([$userId, $item['category'], $item['amount'], $item['enabled'] ? 1 : 0]);
    }
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['user_id'];
    if (isset($_GET['income'])) {
        // Ambil pemasukan bulanan
        $stmt = $pdo->prepare("SELECT monthly_income FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $income = $stmt->fetchColumn();
        echo json_encode(['monthlyIncome' => $income]);
        exit;
    }
    $stmt = $pdo->prepare("SELECT category, amount, enabled FROM budgets WHERE user_id = ?");
    $stmt->execute([$userId]);
    $budget = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['budget' => $budget]);
    exit;
}
?> 