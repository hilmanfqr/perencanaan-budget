<?php
session_start();
require_once 'auth.php';
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costonomy - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="currentColor"/>
                </svg>
                Costonomy
            </div>
            <nav class="nav">
                <a href="#" class="nav-link active" onclick="showPage('loginPage')">Login</a>
                <a href="#" class="nav-link" onclick="showPage('signupPage')">Sign Up</a>
            </nav>
        </header>
        <div id="app">
            <div id="loginPage" class="active">
                <div class="card">
                    <h2 class="card-title">Masuk</h2>
                    <form id="loginForm" onsubmit="return handleLogin(event)">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" placeholder="Masukkan email Anda" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi</label>
                            <input type="password" class="form-input" name="password" placeholder="Masukkan kata sandi Anda" required>
                        </div>
                        <button type="submit" class="btn">Masuk</button>
                        <div class="form-footer">
                            Belum punya akun? <a href="#" onclick="showPage('signupPage')">Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
            <div id="signupPage">
                <div class="card">
                    <h2 class="card-title">Daftar</h2>
                    <form id="signupForm" onsubmit="return handleSignup(event)">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-input" name="name" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" placeholder="Masukkan email Anda" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi</label>
                            <input type="password" class="form-input" name="password" placeholder="Buat kata sandi" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" class="form-input" name="confirmPassword" placeholder="Konfirmasi kata sandi Anda" required>
                        </div>
                        <button type="submit" class="btn">Daftar</button>
                        <div class="form-footer">
                            Sudah punya akun? <a href="#" onclick="showPage('loginPage')">Masuk</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showPage(pageId) {
            const pages = ['loginPage', 'signupPage'];
            pages.forEach(page => {
                document.getElementById(page).classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.textContent.toLowerCase().includes(pageId.replace('Page', '').toLowerCase())) {
                    link.classList.add('active');
                }
            });
        }
        async function handleLogin(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            formData.append('action', 'login');
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
            return false;
        }
        async function handleSignup(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            if (formData.get('password') !== formData.get('confirmPassword')) {
                alert('Kata sandi tidak cocok!');
                return false;
            }
            formData.append('action', 'register');
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    alert('Registrasi berhasil! Silakan login.');
                    showPage('loginPage');
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
            return false;
        }
        document.addEventListener('DOMContentLoaded', function() {
            showPage('loginPage');
        });
    </script>
</body>
</html> 