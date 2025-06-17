<?php
// Penjelasan: Halaman utama dashboard Costonomy (frontend)
// Menampilkan dashboard, edit anggaran, manajemen anggaran, dan about
session_start();
require_once 'auth.php';

// Redirect ke login jika belum login
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costonomy - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js Datalabels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <style>
    /* Gradient text for datalabels */
    .chartjs-datalabel-gradient {
        font-size: 10px !important;
        font-weight: normal !important;
        background: linear-gradient(90deg, #9c4dcc, #6a1b9a, #38006b, #9575cd, #7e57c2, #5e35b1, #ba68c8, #8e24aa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-fill-color: transparent;
    }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <!-- Logo aplikasi -->
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="currentColor"/>
                </svg>
                Costonomy
            </div>
            <nav class="nav">
                <!-- Navigasi utama -->
                <a href="#" class="nav-link active" onclick="showPage('dashboardPage')">Dashboard</a>
                <a href="#" class="nav-link" onclick="showPage('editBudgetPage')">Edit Budget</a>
                <a href="#" class="nav-link" onclick="showPage('managementPage')">Management Result</a>
                <a href="#" class="nav-link" onclick="showPage('aboutPage')">About</a>
                <a href="#" class="nav-link" onclick="handleLogout()">Logout</a>
            </nav>
        </header>
        
        <div id="app">
            <!-- Dashboard Page -->
            <div id="dashboardPage">
                <h1>Beranda</h1>
                <p style="font-size:18px;margin-bottom:24px;">Hai <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'User'); ?>!</p>
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <a href="#" class="dashboard-btn" onclick="showPage('editBudgetPage')">Edit Anggaran</a>
                        <a href="#" class="dashboard-btn" onclick="showPage('managementPage')">Lihat Anggaran</a>
                        <a href="#" class="dashboard-btn" onclick="showPage('aboutPage')">Tentang</a>
                    </div>
                </div>
            </div>
            
            <!-- Edit Budget Page -->
            <div id="editBudgetPage" style="display:none;">
                <div class="card card-wide">
                    <h2 class="card-title">Edit Anggaran</h2>
                    <div class="form-group">
                        <label class="form-label">Pemasukan Bulanan</label>
                        <input type="number" class="form-input" id="monthlyIncomeInput" placeholder="Masukkan pemasukan bulanan">
                        <button type="button" class="btn mt-20" onclick="autoDistributeBudget()">Bagi Otomatis</button>
                    </div>
                    <form class="budget-form" id="budgetForm">
                        <!-- Budget rows will be dynamically generated -->
                    </form>
                    <button type="button" class="btn mt-20" onclick="saveBudget()">Simpan Anggaran</button>
                </div>
            </div>
            
            <!-- Management Page -->
            <div id="managementPage" style="display:none;">
                <div class="card card-wide">
                    <h2 class="card-title">Manajemen Anggaran</h2>
                    <div class="tab-container">
                        <div class="tabs">
                            <div class="tab active" onclick="switchTab(this, 0)">Diagram</div>
                            <div class="tab" onclick="switchTab(this, 1)">Tabel</div>
                        </div>
                        <div class="tab-content active" id="chartTab">
                            <div class="chart-container">
                                <div class="chart-legend-flex" style="display:flex;align-items:center;justify-content:center;gap:32px;">
                                    <div class="donut-chart" id="budgetChart" style="position:relative;">
                                        <canvas id="budgetChartCanvas" width="250" height="250"></canvas>
                                        <div id="incomeExpenseInfo" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;width:160px;">
                                            <div style="font-size:14px;color:#666;">Pemasukan</div>
                                            <div id="incomeValue" style="font-size:18px;font-weight:bold;color:#6a1b9a;">Rp 0</div>
                                            <div style="font-size:14px;color:#666;margin-top:4px;">Pengeluaran</div>
                                            <div id="expenseValue" style="font-size:18px;font-weight:bold;color:#e57373;">Rp 0</div>
                                            <div style="font-size:14px;color:#666;margin-top:4px;">Sisa Budget</div>
                                            <div id="remainingValue" style="font-size:18px;font-weight:bold;color:#4caf50;">Rp 0</div>
                                        </div>
                                    </div>
                                    <div id="categoryLegend" class="category-legend"></div>
                                </div>
                            </div>
                            <table class="budget-table" id="budgetSummaryTable">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah (Rp)</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table rows will be dynamically generated -->
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-content" id="tableTab">
                            <table class="budget-table" id="budgetDetailTable">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah (Rp)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table rows will be dynamically generated -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- About Page -->
            <div id="aboutPage" style="display:none;">
                <div class="card">
                    <h2 class="card-title">Tentang</h2>
                    <div class="text-center">
                        <svg viewBox="0 0 100 100" width="150" height="150" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50,10 A40,40 0 1,0 50,90 A40,40 0 1,0 50,10 Z" fill="none" stroke="#6a1b9a" stroke-width="4"/>
                            <path d="M30,50 C30,30 70,30 70,50 C70,70 30,70 30,50 Z" fill="none" stroke="#6a1b9a" stroke-width="4"/>
                        </svg>
                    </div>
                    <p class="text-center mt-20">
                        Costonomy v1.0.0<br>
                        Alat sederhana untuk membantu mengatur keuangan anak kos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let budgetData = [];
        let monthlyIncome = 0;
        
        // Colors for the chart
        const chartColors = [
            "#9c4dcc", "#6a1b9a", "#38006b", "#9575cd", 
            "#7e57c2", "#5e35b1", "#ba68c8", "#8e24aa"
        ];
        
        // Fungsi untuk berpindah halaman (Dashboard, Edit Budget, dsb)
        function showPage(pageId) {
            const pages = ['dashboardPage', 'editBudgetPage', 'managementPage', 'aboutPage'];
            pages.forEach(page => {
                document.getElementById(page).style.display = 'none';
            });
            document.getElementById(pageId).style.display = 'block';
            
            // Update active navigation link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.textContent.toLowerCase().includes(pageId.replace('Page', '').toLowerCase())) {
                    link.classList.add('active');
                }
            });

            // Center card only on Edit Budget page
            if (pageId === 'editBudgetPage') {
                document.body.classList.add('edit-budget-center');
                populateBudgetForm();
                loadMonthlyIncome();
                setTimeout(() => {
                    const incomeInput = document.getElementById('monthlyIncomeInput');
                    if (incomeInput && !incomeInput.dataset.listener) {
                        incomeInput.addEventListener('change', saveMonthlyIncome);
                        incomeInput.dataset.listener = '1';
                    }
                }, 300);
            } else {
                document.body.classList.remove('edit-budget-center');
            }

            // If navigating to management page, update the budget visualization
            if (pageId === 'managementPage') {
                updateBudgetVisualization();
            }
        }
        
        // Fungsi untuk menyimpan data anggaran ke backend
        function saveBudget() {
            const budgetRows = document.querySelectorAll('.budget-row');
            budgetData = [];
            
            // Hitung total budget untuk persentase
            let totalBudget = 0;
            budgetRows.forEach(row => {
                const inputs = row.querySelectorAll('.form-input');
                const toggle = row.querySelector('input[type="checkbox"]');
                if (toggle.checked) {
                    totalBudget += parseFloat(inputs[1].value) || 0;
                }
            });
            
            // Kumpulkan data budget
            budgetRows.forEach((row, index) => {
                const inputs = row.querySelectorAll('.form-input');
                const toggle = row.querySelector('input[type="checkbox"]');
                const amount = parseFloat(inputs[1].value) || 0;
                const percentage = totalBudget > 0 ? (amount / totalBudget) * 100 : 0;
                
                budgetData.push({
                    category: inputs[0].value.trim(),
                    amount: amount,
                    enabled: toggle.checked,
                    percentage: percentage,
                    color: chartColors[index % chartColors.length]
                });
            });
            
            fetch('budget.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(budgetData)
            }).then(() => {
                showPage('managementPage');
                alert('Anggaran berhasil disimpan!');
                updateBudgetVisualization();
            });
        }
        
        function switchTab(tabElement, tabIndex) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            tabElement.classList.add('active');
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            tabContents[tabIndex].classList.add('active');
        }
        
        function populateBudgetForm() {
            fetch('budget.php')
                .then(res => res.json())
                .then(data => {
                    budgetData = data.budget || [];
                    const budgetForm = document.getElementById('budgetForm');
                    budgetForm.innerHTML = '';
                    budgetData.forEach((item, index) => {
                        budgetForm.innerHTML += `
                        <div class="budget-row">
                            <label>Kategori</label>
                            <input type="text" class="form-input" value="${item.category}">
                            <input type="number" class="form-input" value="${item.amount}">
                            <label class="toggle-switch">
                                <input type="checkbox" ${item.enabled ? 'checked' : ''}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>`;
                    });
                });
        }
        
        function updateBudgetVisualization() {
            Promise.all([
                fetch('budget.php').then(res => res.json()),
                fetch('budget.php?income=1').then(res => res.json())
            ]).then(([budgetRes, incomeRes]) => {
                budgetData = budgetRes.budget || [];
                monthlyIncome = incomeRes.monthlyIncome || 0;
                updateDonutChart(budgetData);
                updateBudgetTables(budgetData);
            });
        }
        
        let budgetChartInstance = null;
        function formatRupiah(num) {
            return num.toLocaleString('id-ID');
        }
        function updateDonutChart(data) {
            const ctx = document.getElementById('budgetChartCanvas').getContext('2d');
            const enabledData = data.filter(item => item.enabled);
            const total = enabledData.reduce((sum, item) => sum + Number(item.amount), 0);
            const chartData = {
                labels: enabledData.map(item => item.category),
                datasets: [{
                    data: enabledData.map(item => item.amount),
                    backgroundColor: enabledData.map((item, idx) => chartColors[idx % chartColors.length]),
                    borderWidth: 8,
                    borderColor: '#fff',
                    hoverOffset: 16,
                }]
            };
            const options = {
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const percent = total > 0 ? Math.round((value/total)*100) : 0;
                                return `${label}: Rp ${formatRupiah(value)} (${percent}%)`;
                            }
                        },
                        backgroundColor: '#fff',
                        titleColor: '#333',
                        bodyColor: '#333',
                        borderColor: '#ccc',
                        borderWidth: 1
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            };
            if (budgetChartInstance) budgetChartInstance.destroy();
            budgetChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: chartData,
                options: options,
                plugins: [ChartDataLabels]
            });
            // Update info pemasukan/pengeluaran/sisa
            document.getElementById('incomeValue').textContent = 'Rp ' + formatRupiah(Math.round(monthlyIncome || 0));
            document.getElementById('expenseValue').textContent = 'Rp ' + formatRupiah(Math.round(total));
            document.getElementById('remainingValue').textContent = 'Rp ' + formatRupiah(Math.round((monthlyIncome || 0) - total));
            // Update legend kategori
            const legend = document.getElementById('categoryLegend');
            legend.innerHTML = enabledData.map((item, idx) =>
                `<div style=\"display:flex;align-items:center;margin-bottom:8px;\">
                    <span style=\"display:inline-block;width:16px;height:16px;background:${chartColors[idx % chartColors.length]};border-radius:4px;margin-right:8px;\"></span>
                    <span style=\"font-size:14px;color:#333;\">${item.category}</span>
                </div>`
            ).join('');
        }
        
        function updateBudgetTables(data) {
            // Summary Table
            const summaryTableBody = document.querySelector('#budgetSummaryTable tbody');
            summaryTableBody.innerHTML = '';
            const enabledData = data.filter(item => item.enabled);
            const total = enabledData.reduce((sum, item) => sum + Number(item.amount), 0);
            enabledData.forEach(item => {
                const percent = total > 0 ? Math.round(item.amount / total * 100) : 0;
                const row = document.createElement('tr');
                row.innerHTML = `<td>${item.category}</td><td>${Number(item.amount).toLocaleString()}</td><td>${percent}%</td>`;
                summaryTableBody.appendChild(row);
            });
            // Detail Table
            const detailTableBody = document.querySelector('#budgetDetailTable tbody');
            detailTableBody.innerHTML = '';
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${item.category}</td><td>${Number(item.amount).toLocaleString()}</td><td>${item.enabled ? 'Active' : 'Inactive'}</td>`;
                detailTableBody.appendChild(row);
            });
        }
        
        function handleLogout() {
            fetch('auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=logout'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'index.php';
                }
            });
        }
        
        function saveMonthlyIncome() {
            const input = document.getElementById('monthlyIncomeInput');
            if (input) {
                monthlyIncome = parseFloat(input.value) || 0;
                fetch('budget.php?income=1', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ monthlyIncome })
                });
            }
        }
        
        function loadMonthlyIncome() {
            fetch('budget.php?income=1')
                .then(res => res.json())
                .then(data => {
                    monthlyIncome = data.monthlyIncome || 0;
                    const input = document.getElementById('monthlyIncomeInput');
                    if (input) input.value = monthlyIncome;
                });
        }
        
        function autoDistributeBudget() {
            // Contoh logika: distribusi otomatis berdasarkan persentase default
            const input = document.getElementById('monthlyIncomeInput');
            const income = parseFloat(input.value) || 0;
            const defaultPercent = [30, 20, 10, 10, 15, 15];
            const budgetForm = document.getElementById('budgetForm');
            const rows = budgetForm.querySelectorAll('.budget-row');
            rows.forEach((row, i) => {
                const amountInput = row.querySelectorAll('.form-input')[1];
                if (amountInput && defaultPercent[i] !== undefined) {
                    amountInput.value = Math.round(income * defaultPercent[i] / 100);
                }
            });
        }
        
        // Inisialisasi awal
        document.addEventListener('DOMContentLoaded', function() {
            showPage('dashboardPage');
            updateBudgetVisualization();
        });
    </script>
</body>
</html>
