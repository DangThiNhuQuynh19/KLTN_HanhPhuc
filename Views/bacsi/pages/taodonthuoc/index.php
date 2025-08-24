<?php
// Khởi tạo session

// Giả lập dữ liệu bệnh nhân (trong thực tế sẽ lấy từ database)
$patients = [
    ["id" => "BN123456", "name" => "Nguyễn Văn A", "dob" => "01/01/1980", "gender" => "Nam", "phone" => "0912345678", "allergies" => "Penicillin"],
    ["id" => "BN234567", "name" => "Trần Thị B", "dob" => "15/05/1985", "gender" => "Nữ", "phone" => "0923456789", "allergies" => "Không"],
    ["id" => "BN345678", "name" => "Lê Văn C", "dob" => "20/10/1975", "gender" => "Nam", "phone" => "0934567890", "allergies" => "Aspirin"],
    ["id" => "BN456789", "name" => "Phạm Thị D", "dob" => "12/12/1990", "gender" => "Nữ", "phone" => "0945678901", "allergies" => "Không"]
];

// Giả lập dữ liệu thuốc (trong thực tế sẽ lấy từ database)
$medications = [
    ["id" => "T001", "name" => "Paracetamol", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "2000"],
    ["id" => "T002", "name" => "Amoxicillin", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "5000"],
    ["id" => "T003", "name" => "Omeprazole", "form" => "Viên", "strength" => "20mg", "unit" => "Viên", "price" => "8000"],
    ["id" => "T004", "name" => "Cetirizine", "form" => "Viên", "strength" => "10mg", "unit" => "Viên", "price" => "3000"],
    ["id" => "T005", "name" => "Metformin", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "4000"],
    ["id" => "T006", "name" => "Amlodipine", "form" => "Viên", "strength" => "5mg", "unit" => "Viên", "price" => "6000"],
    ["id" => "T007", "name" => "Atorvastatin", "form" => "Viên", "strength" => "10mg", "unit" => "Viên", "price" => "10000"],
    ["id" => "T008", "name" => "Salbutamol", "form" => "Ống hít", "strength" => "100mcg", "unit" => "Ống", "price" => "120000"],
    ["id" => "T009", "name" => "Ibuprofen", "form" => "Viên", "strength" => "400mg", "unit" => "Viên", "price" => "3000"],
    ["id" => "T010", "name" => "Loratadine", "form" => "Viên", "strength" => "10mg", "unit" => "Viên", "price" => "5000"],
    ["id" => "T011", "name" => "Vitamin C", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "2000"],
    ["id" => "T012", "name" => "Vitamin D3", "form" => "Viên", "strength" => "1000IU", "unit" => "Viên", "price" => "3000"],
    ["id" => "T013", "name" => "Calcium", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "4000"],
    ["id" => "T014", "name" => "Metronidazole", "form" => "Viên", "strength" => "250mg", "unit" => "Viên", "price" => "3500"],
    ["id" => "T015", "name" => "Ciprofloxacin", "form" => "Viên", "strength" => "500mg", "unit" => "Viên", "price" => "7000"]
];

// Giả lập dữ liệu bác sĩ (trong thực tế sẽ lấy từ database)
$doctors = [
    ["id" => "BS001", "name" => "BS. Trần Văn B", "department" => "Khoa Nội Tổng Hợp"],
    ["id" => "BS002", "name" => "BS. Lê Thị C", "department" => "Khoa Nhi"],
    ["id" => "BS003", "name" => "BS. Phạm Văn D", "department" => "Khoa Tim Mạch"],
    ["id" => "BS004", "name" => "BS. Hoàng Thị E", "department" => "Khoa Thần Kinh"]
];

// Xử lý form khi submit
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Trong thực tế, bạn sẽ lưu dữ liệu vào database
    $message = "Đơn thuốc đã được tạo thành công! Mã đơn thuốc: DT" . rand(100000, 999999);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đơn Thuốc - Bệnh Viện Hạnh Phúc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #5e4b93;
            --primary-dark: #483a73;
            --primary-light: #f0ecff;
            --secondary: #7c6db3;
            --secondary-light: #f5f2ff;
            --accent: #8a7fc7;
            --accent-light: #f8f6ff;
            --success: #4caf50;
            --success-light: #e8f5e9;
            --warning: #ff9800;
            --warning-light: #fff3e0;
            --danger: #f44336;
            --danger-light: #ffebee;
            --dark: #2d3748;
            --gray: #718096;
            --light-gray: #edf2f7;
            --white: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: var(--dark);
            background-color: #f7fafc;
        }

        a {
            text-decoration: none;
            color: var(--primary);
            transition: var(--transition);
        }

        a:hover {
            color: var(--primary-dark);
        }

        ul {
            list-style: none;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            height: 70px;
            background-color: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
        }

        .logo i {
            color: var(--primary);
            margin-right: 12px;
            font-size: 28px;
        }

        .main-nav ul {
            display: flex;
        }

        .main-nav li {
            margin: 0 12px;
        }

        .main-nav a {
            color: var(--gray);
            padding: 10px 15px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 500;
            position: relative;
        }

        .main-nav a:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .main-nav a.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 600;
        }

        .main-nav a.active::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }

        .user-menu {
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .user-info:hover {
            background-color: var(--light-gray);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 12px;
            object-fit: cover;
            background-color: var(--primary-light);
            border: 2px solid var(--primary);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 220px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 10;
            overflow: hidden;
            margin-top: 10px;
            border: 1px solid var(--light-gray);
        }

        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -8px;
            right: 20px;
            width: 16px;
            height: 16px;
            background-color: var(--white);
            transform: rotate(45deg);
            border-top: 1px solid var(--light-gray);
            border-left: 1px solid var(--light-gray);
        }

        .user-menu:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--dark);
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .dropdown-menu a:hover {
            background-color: var(--primary-light);
            border-left-color: var(--primary);
        }

        .dropdown-menu i {
            margin-right: 12px;
            color: var(--primary);
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Footer */
        .main-footer {
            background-color: var(--white);
            border-top: 1px solid var(--light-gray);
            padding: 20px;
            margin-top: 40px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links a {
            margin-left: 20px;
            color: var(--gray);
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        /* Buttons */
        .btn-primary,
        .btn-outline,
        .btn-small,
        .btn-success,
        .btn-danger,
        .btn-warning {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            text-align: center;
            font-size: 14px;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
            box-shadow: 0 2px 4px rgba(94, 75, 147, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 4px 8px rgba(94, 75, 147, 0.4);
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: var(--success);
            color: var(--white);
            box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);
        }

        .btn-success:hover {
            background-color: #43a047;
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: var(--white);
            box-shadow: 0 2px 4px rgba(244, 67, 54, 0.3);
        }

        .btn-danger:hover {
            background-color: #e53935;
            box-shadow: 0 4px 8px rgba(244, 67, 54, 0.4);
            transform: translateY(-1px);
        }

        .btn-warning {
            background-color: var(--warning);
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(255, 152, 0, 0.3);
        }

        .btn-warning:hover {
            background-color: #fb8c00;
            box-shadow: 0 4px 8152,0,0.3);
        }

        .btn-warning:hover {
            background-color: #fb8c00;
            box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4);
            transform: translateY(-1px);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-dark);
            color: var(--primary-dark);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--white);
            color: var(--primary);
            border: 1px solid var(--light-gray);
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .btn-icon:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-full {
            width: 100%;
            margin-top: 12px;
        }

        /* Cards */
        .card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--light-gray);
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--light-gray);
            background-color: #fcfcfc;
        }

        .card-header h2 {
            margin: 0;
            font-size: 18px;
            color: var(--dark);
            font-weight: 600;
        }

        .card-header p {
            margin: 5px 0 0;
            color: var(--gray);
            font-size: 13px;
        }

        .card-body {
            padding: 24px;
        }

        /* Content Header */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .content-header h1 {
            color: var(--dark);
            font-size: 28px;
            font-weight: 700;
        }

        .back-button {
            display: flex;
            align-items: center;
        }

        .back-button a {
            margin-right: 16px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) inset;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px;
        }

        /* Medication List */
        .medication-list {
            margin-bottom: 24px;
        }

        .medication-item {
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            padding: 16px;
            margin-bottom: 16px;
            position: relative;
        }

        .medication-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .medication-name {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 16px;
        }

        .medication-actions {
            display: flex;
            gap: 8px;
        }

        .medication-details {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 12px;
        }

        .medication-detail {
            flex: 1;
            min-width: 150px;
        }

        .detail-label {
            font-size: 12px;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .detail-value {
            font-weight: 500;
        }

        .medication-instructions {
            background-color: var(--primary-light);
            padding: 12px;
            border-radius: var(--border-radius);
            margin-top: 12px;
        }

        .remove-medication {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            font-size: 16px;
            transition: var(--transition);
        }

        .remove-medication:hover {
            transform: scale(1.2);
        }

        /* Medication Search */
        .medication-search {
            position: relative;
            margin-bottom: 16px;
        }

        .medication-search input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) inset;
        }

        .medication-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .medication-search input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .medication-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 10;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .medication-results.active {
            display: block;
        }

        .medication-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--light-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .medication-result-item:last-child {
            border-bottom: none;
        }

        .medication-result-item:hover {
            background-color: var(--primary-light);
        }

        .medication-result-name {
            font-weight: 500;
            color: var(--dark);
        }

        .medication-result-details {
            font-size: 12px;
            color: var(--gray);
            margin-top: 4px;
        }

        /* Alert Messages */
        .alert {
            padding: 16px;
            border-radius: var(--border-radius);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 12px;
            font-size: 20px;
        }

        .alert-success {
            background-color: var(--success-light);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-warning {
            background-color: var(--warning-light);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background-color: var(--primary-light);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .alert-danger {
            background-color: var(--danger-light);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        /* Summary */
        .summary-section {
            background-color: var(--primary-light);
            padding: 16px;
            border-radius: var(--border-radius);
            margin-top: 24px;
        }

        .summary-header {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }

        .summary-items {
            margin-bottom: 16px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--light-gray);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: var(--primary-dark);
            padding-top: 12px;
            border-top: 2px solid var(--primary);
        }

        /* Prescription Preview */
        .prescription-preview {
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            padding: 24px;
            margin-top: 24px;
            box-shadow: var(--shadow);
        }

        .prescription-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--primary);
        }

        .prescription-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .prescription-subtitle {
            font-size: 14px;
            color: var(--gray);
        }

        .prescription-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .prescription-patient,
        .prescription-doctor {
            flex: 1;
        }

        .prescription-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .prescription-value {
            margin-bottom: 8px;
        }

        .prescription-medications {
            margin-bottom: 24px;
        }

        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .prescription-table th,
        .prescription-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        .prescription-table th {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .prescription-notes {
            margin-bottom: 24px;
        }

        .prescription-signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid var(--dark);
            margin-bottom: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
            
            .form-col {
                margin-bottom: 16px;
            }
            
            .prescription-info {
                flex-direction: column;
            }
            
            .prescription-patient,
            .prescription-doctor {
                margin-bottom: 16px;
            }
            
            .prescription-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    

    <!-- Main Content -->
    <main class="container">
        <!-- Page Header -->
        <div class="content-header">
            <div class="back-button">
                <a href="prescriptions.php" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Thêm Đơn Thuốc</h1>
            </div>
            
            <div class="action-buttons">
                <a href="prescriptions.php" class="btn-small">
                    <i class="fas fa-list"></i> Danh sách đơn thuốc
                </a>
            </div>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Thành công!</strong> <?php echo $message; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Lưu ý:</strong> Vui lòng kiểm tra kỹ thông tin bệnh nhân và thuốc trước khi lưu đơn thuốc.
            </div>
        </div>

        <!-- Prescription Form -->
        <form action="" method="post" id="prescriptionForm">
            <div class="card">
                <div class="card-header">
                    <h2>Thông Tin Bệnh Nhân</h2>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientId">Mã bệnh nhân</label>
                                <select name="patientId" id="patientId" required>
                                    <option value="">-- Chọn bệnh nhân --</option>
                                    <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient['id']; ?>"><?php echo $patient['id'] . ' - ' . $patient['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientName">Họ và tên</label>
                                <input type="text" id="patientName" name="patientName" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientDob">Ngày sinh</label>
                                <input type="text" id="patientDob" name="patientDob" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientGender">Giới tính</label>
                                <input type="text" id="patientGender" name="patientGender" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientAllergies">Dị ứng</label>
                                <input type="text" id="patientAllergies" name="patientAllergies" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Thông Tin Đơn Thuốc</h2>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="prescriptionDate">Ngày kê đơn</label>
                                <input type="date" id="prescriptionDate" name="prescriptionDate" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="diagnosis">Chẩn đoán</label>
                                <input type="text" id="diagnosis" name="diagnosis" required placeholder="Nhập chẩn đoán">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="doctorId">Bác sĩ kê đơn</label>
                                <select name="doctorId" id="doctorId" required>
                                    <option value="">-- Chọn bác sĩ --</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo $doctor['id']; ?>"><?php echo $doctor['name'] . ' - ' . $doctor['department']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="validDays">Hiệu lực (ngày)</label>
                                <input type="number" id="validDays" name="validDays" min="1" max="30" value="7" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Danh Sách Thuốc</h2>
                </div>
                <div class="card-body">
                    <div class="medication-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="medicationSearch" placeholder="Tìm kiếm thuốc..." autocomplete="off">
                        <div class="medication-results" id="medicationResults"></div>
                    </div>

                    <div id="medicationList" class="medication-list">
                        <div class="alert alert-info" id="noMedicationsMessage">
                            <i class="fas fa-info-circle"></i>
                            <div>Chưa có thuốc nào được thêm vào đơn. Vui lòng tìm kiếm và thêm thuốc.</div>
                        </div>
                    </div>

                    <button type="button" class="btn-primary" id="showMedicationModal">
                        <i class="fas fa-plus"></i> Thêm thuốc mới
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Ghi Chú & Hướng Dẫn</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="notes">Ghi chú cho bệnh nhân</label>
                        <textarea name="notes" id="notes" rows="4" placeholder="Nhập ghi chú và hướng dẫn cho bệnh nhân..."></textarea>
                    </div>
                </div>
            </div>

            <div class="summary-section">
                <div class="summary-header">
                    <span>Tóm tắt đơn thuốc</span>
                    <span id="medicationCount">0 loại thuốc</span>
                </div>
                <div class="summary-items" id="summaryItems">
                    <div class="summary-item">
                        <span>Chưa có thuốc nào được thêm vào đơn</span>
                        <span>0 VNĐ</span>
                    </div>
                </div>
                <div class="summary-total">
                    <span>Tổng cộng</span>
                    <span id="totalPrice">0 VNĐ</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 24px;">
                <button type="button" class="btn-outline" id="previewButton">
                    <i class="fas fa-eye"></i> Xem trước
                </button>
                <div>
                    <button type="reset" class="btn-outline">
                        <i class="fas fa-redo"></i> Làm mới
                    </button>
                    <button type="submit" name="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu đơn thuốc
                    </button>
                </div>
            </div>
        </form>

        <!-- Prescription Preview (Hidden by default) -->
        <div id="prescriptionPreview" class="prescription-preview" style="display: none;">
            <div class="prescription-header">
                <h2 class="prescription-title">ĐƠN THUỐC</h2>
                <p class="prescription-subtitle">Bệnh Viện Hạnh Phúc</p>
                <p class="prescription-subtitle">123 Đường XYZ, Quận ABC, TP. Hồ Chí Minh</p>
                <p class="prescription-subtitle">Điện thoại: (028) 1234 5678</p>
            </div>

            <div class="prescription-info">
                <div class="prescription-patient">
                    <div class="prescription-label">Thông tin bệnh nhân:</div>
                    <div class="prescription-value">Họ tên: <span id="previewPatientName"></span></div>
                    <div class="prescription-value">Mã BN: <span id="previewPatientId"></span></div>
                    <div class="prescription-value">Ngày sinh: <span id="previewPatientDob"></span></div>
                    <div class="prescription-value">Giới tính: <span id="previewPatientGender"></span></div>
                    <div class="prescription-value">Dị ứng: <span id="previewPatientAllergies"></span></div>
                </div>

                <div class="prescription-doctor">
                    <div class="prescription-label">Thông tin đơn thuốc:</div>
                    <div class="prescription-value">Ngày kê đơn: <span id="previewPrescriptionDate"></span></div>
                    <div class="prescription-value">Chẩn đoán: <span id="previewDiagnosis"></span></div>
                    <div class="prescription-value">Bác sĩ: <span id="previewDoctor"></span></div>
                    <div class="prescription-value">Hiệu lực: <span id="previewValidDays"></span> ngày</div>
                </div>
            </div>

            <div class="prescription-medications">
                <div class="prescription-label">Danh sách thuốc:</div>
                <table class="prescription-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên thuốc</th>
                            <th>Liều lượng</th>
                            <th>Số lượng</th>
                            <th>Đơn vị</th>
                            <th>Hướng dẫn sử dụng</th>
                        </tr>
                    </thead>
                    <tbody id="previewMedicationList">
                        <!-- Medications will be added here dynamically -->
                    </tbody>
                </table>
            </div>

            <div class="prescription-notes">
                <div class="prescription-label">Ghi chú & Hướng dẫn:</div>
                <div id="previewNotes"></div>
            </div>

            <div class="prescription-signature">
                <div class="signature-box">
                    <div>Ngày <?php echo date('d'); ?> tháng <?php echo date('m'); ?> năm <?php echo date('Y'); ?></div>
                    <div>Bác sĩ kê đơn</div>
                    <div style="height: 80px;"></div>
                    <div class="signature-line"></div>
                    <div id="previewDoctorName"></div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 24px;">
                <button type="button" class="btn-outline" id="closePreviewButton">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn-primary" id="printPreviewButton">
                    <i class="fas fa-print"></i> In đơn thuốc
                </button>
            </div>
        </div>

        <!-- Add Medication Modal (Hidden by default) -->
        <div id="medicationModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 1000;">
            <div style="background-color: white; width: 90%; max-width: 600px; margin: 100px auto; padding: 24px; border-radius: 8px; box-shadow: var(--shadow-lg);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 style="margin: 0;">Thêm Thuốc</h2>
                    <button type="button" class="btn-icon" id="closeModalButton">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label for="modalMedicationName">Tên thuốc</label>
                    <input type="text" id="modalMedicationName" required>
                </div>

                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="modalMedicationForm">Dạng thuốc</label>
                            <select id="modalMedicationForm" required>
                                <option value="">-- Chọn dạng thuốc --</option>
                                <option value="Viên">Viên</option>
                                <option value="Ống">Ống</option>
                                <option value="Gói">Gói</option>
                                <option value="Chai">Chai</option>
                                <option value="Tuýp">Tuýp</option>
                                <option value="Ống hít">Ống hít</option>
                            </select>
                        </div>
                    </div>
                    <div class