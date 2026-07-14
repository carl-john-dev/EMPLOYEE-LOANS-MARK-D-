<?php
include 'connection.php';

// Get current tab from URL
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'active';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>EMPLOYEE LOANS</title>
    <link rel="icon" type="icon" href="MDUE LOGO.jpg"/>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet"/>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body {
            background-color: #f5f5f5;
            padding: 20px;
            transition: background-color 0.3s, color 0.3s;
            padding-top: 120px;
        }
        body.dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }
        .container-custom { max-width: 1400px; margin: 0 auto; }

        /* ===== STICKY HEADER ===== */
        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: #f5f5f5;
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        body.dark-mode .sticky-header {
            background-color: #1a1a2e;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }
        .sticky-header .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        .sticky-logo img {
            height: 50px;
            width: auto;
            max-height: 60px;
            object-fit: fill;
            border-radius: 0.5px;
            transition: all 0.3s ease;
        }
        body.dark-mode .sticky-logo img {
            filter: brightness(0.95) drop-shadow(0 0 4px rgba(255,255,240,0.15));
        }
        .sticky-header.hidden {
            transform: translateY(-100%);
            opacity: 0;
        }
        .logo-stretch img {
            height: 80px;
            width: 1150px;
            max-height: 90px;
            object-fit: fill;
            border-radius: 0.5px;
            transition: filter 0.3s;
        }
        body.dark-mode .logo-stretch img {
            filter: brightness(0.95) drop-shadow(0 0 4px rgba(255,255,240,0.15));
        }
        .card-custom {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        body.dark-mode .card-custom {
            background: #16213e;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }
        .form-control-custom {
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        body.dark-mode .form-control-custom {
            background-color: #1a1a3e;
            border-color: #2a2a4a;
            color: #e0e0e0;
        }
        body.dark-mode .form-control-custom:focus {
            background-color: #1a1a3e;
            color: #e0e0e0;
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        body.dark-mode .form-control-custom::placeholder {
            color: #8888aa;
        }
        body.dark-mode .form-label {
            color: #e0e0e0;
        }
        .sticky-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: #2c3e50;
            white-space: nowrap;
        }
        body.dark-mode .sticky-title { color: #e0e0e0; }
        .sticky-title i { margin-right: 8px; }
        .sticky-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .sticky-actions .btn-sm {
            font-size: 0.8rem;
            padding: 4px 10px;
        }

        /* ===== THEME TOGGLE ===== */
        .theme-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .theme-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-color);
        }
        .theme-switch {
            --toggle-size: 20px;
            --container-width: 3.75em;
            --container-height: 1.8em;
            --container-radius: 4em;
            --container-light-bg: #3D7EAE;
            --container-night-bg: #1D1F2C;
            --circle-container-diameter: 2.2em;
            --sun-moon-diameter: 1.5em;
            --sun-bg: #ECCA2F;
            --moon-bg: #C4C9D1;
            --spot-color: #959DB1;
            --circle-container-offset: calc((var(--circle-container-diameter) - var(--container-height)) / 2 * -1);
            --stars-color: #fff;
            --clouds-color: #F3FDFF;
            --back-clouds-color: #AACADF;
            --transition: .4s cubic-bezier(0, -0.02, 0.4, 1.25);
            --circle-transition: .3s cubic-bezier(0, -0.02, 0.35, 1.17);
        }
        .theme-switch, .theme-switch *, .theme-switch *::before, .theme-switch *::after {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-size: var(--toggle-size);
        }
        .theme-switch__container {
            width: var(--container-width);
            height: var(--container-height);
            background-color: var(--container-light-bg);
            border-radius: var(--container-radius);
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
            transition: var(--transition);
            position: relative;
        }
        .theme-switch__container::before {
            content: "";
            position: absolute;
            z-index: 1;
            inset: 0;
            box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
            border-radius: var(--container-radius);
        }
        .theme-switch__checkbox { display: none; }
        .theme-switch__circle-container {
            width: var(--circle-container-diameter);
            height: var(--circle-container-diameter);
            background-color: rgba(255, 255, 255, 0.1);
            position: absolute;
            left: var(--circle-container-offset);
            top: var(--circle-container-offset);
            border-radius: var(--container-radius);
            box-shadow: inset 0 0 0 2.5em rgba(255, 255, 255, 0.1), 0 0 0 0.4em rgba(255, 255, 255, 0.1);
            display: flex;
            transition: var(--circle-transition);
            pointer-events: none;
        }
        .theme-switch__sun-moon-container {
            pointer-events: auto;
            position: relative;
            z-index: 2;
            width: var(--sun-moon-diameter);
            height: var(--sun-moon-diameter);
            margin: auto;
            border-radius: var(--container-radius);
            background-color: var(--sun-bg);
            box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset;
            filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25));
            overflow: hidden;
            transition: var(--transition);
        }
        .theme-switch__moon {
            transform: translateX(100%);
            width: 100%;
            height: 100%;
            background-color: var(--moon-bg);
            border-radius: inherit;
            box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset;
            transition: var(--transition);
            position: relative;
        }
        .theme-switch__spot {
            position: absolute;
            top: 0.5em;
            left: 0.2em;
            width: 0.5em;
            height: 0.5em;
            border-radius: var(--container-radius);
            background-color: var(--spot-color);
            box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
        }
        .theme-switch__spot:nth-of-type(2) {
            width: 0.25em;
            height: 0.25em;
            top: 0.6em;
            left: 0.9em;
        }
        .theme-switch__spot:nth-last-of-type(3) {
            width: 0.18em;
            height: 0.18em;
            top: 0.2em;
            left: 0.55em;
        }
        .theme-switch__clouds {
            width: 0.9em;
            height: 0.9em;
            background-color: var(--clouds-color);
            border-radius: var(--container-radius);
            position: absolute;
            bottom: -0.4em;
            left: 0.2em;
            box-shadow: 0.6em 0.2em var(--clouds-color), -0.2em -0.2em var(--back-clouds-color), 0.9em 0.25em var(--clouds-color), 0.3em -0.08em var(--back-clouds-color), 1.4em 0 var(--clouds-color), 0.8em -0.04em var(--back-clouds-color), 1.9em 0.2em var(--clouds-color), 1.3em -0.2em var(--back-clouds-color), 2.3em -0.04em var(--clouds-color), 1.7em 0em var(--back-clouds-color), 2.9em -0.2em var(--clouds-color), 2.2em -0.28em var(--back-clouds-color), 3em -1.1em 0 0.28em var(--clouds-color), 2.6em -0.4em var(--back-clouds-color), 2.7em -1.35em 0 0.28em var(--back-clouds-color);
            transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
        }
        .theme-switch__stars-container {
            position: absolute;
            color: var(--stars-color);
            top: -100%;
            left: 0.2em;
            width: 1.8em;
            height: auto;
            transition: var(--transition);
        }
        .theme-switch__stars-container svg {
            width: 100%;
            height: auto;
        }
        .theme-switch__checkbox:checked + .theme-switch__container {
            background-color: var(--container-night-bg);
        }
        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container {
            left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter));
        }
        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
            left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.12em);
        }
        .theme-switch__circle-container:hover {
            left: calc(var(--circle-container-offset) + 0.12em);
        }
        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon {
            transform: translate(0);
        }
        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds {
            bottom: -2.6em;
        }
        .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
            top: 50%;
            transform: translateY(-50%);
        }
        .sticky-actions .theme-switch {
            --toggle-size: 16px;
            --container-width: 3.2em;
            --container-height: 1.6em;
        }

        /* ===== STATUS BADGES ===== */
        .badge-paid {
            background-color: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-unpaid {
            background-color: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-archived {
            background-color: #6c757d;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        body.dark-mode .badge-paid { background-color: #27ae60; }
        body.dark-mode .badge-unpaid { background-color: #c0392b; }
        body.dark-mode .badge-archived { background-color: #4a4a5a; }

        /* DataTables Custom */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 6px 12px;
        }
        body.dark-mode .dataTables_wrapper .dataTables_filter input {
            background-color: #1a1a3e;
            border-color: #2a2a4a;
            color: #e0e0e0;
        }
        body.dark-mode .dataTables_wrapper .dataTables_length select {
            background-color: #1a1a3e;
            border-color: #2a2a4a;
            color: #e0e0e0;
        }
        body.dark-mode .dataTables_wrapper .dataTables_info {
            color: #8888aa;
        }
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #e0e0e0 !important;
        }
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0f3460 !important;
            color: white !important;
        }
        .table-dark-mode.dataTable thead th {
            background-color: #3498db;
            color: white;
        }
        body.dark-mode .table-dark-mode.dataTable thead th {
            background-color: #0f3460;
            color: white;
        }

        /* Export Buttons */
        .dt-buttons .btn {
            border-radius: 6px !important;
            font-size: 13px !important;
            padding: 6px 14px !important;
            margin: 0 4px !important;
        }
        .dt-buttons .btn-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .dt-buttons .btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }
        .dt-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        body.dark-mode .dt-buttons .btn {
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .char-counter {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .char-counter.limit-reached {
            color: #e74c3c;
            font-weight: 600;
        }
        body.dark-mode .char-counter {
            color: #8888aa;
        }

        .export-buttons-wrapper {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        /* Tab buttons */
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .tab-buttons .btn {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        .tab-buttons .btn.active-tab {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }
        body.dark-mode .tab-buttons .btn.active-tab {
            background-color: #0f3460;
            border-color: #0f3460;
        }

        @media (max-width: 768px) {
            .logo-stretch img { height: 60px; max-height: 68px; width: 100%; }
            .sticky-logo img { height: 40px; max-height: 50px; }
            .sticky-title { font-size: 0.9rem; }
            .sticky-actions .btn-sm { font-size: 0.7rem; padding: 3px 8px; }
            .sticky-header { padding: 8px 12px; }
            .sticky-header .header-inner { gap: 8px; }
            body { padding-top: 90px; }
            .container-custom { padding: 0 10px; }
            .export-buttons-wrapper { justify-content: center; }
            .tab-buttons { justify-content: center; }
        }
        @media (max-width: 480px) {
            .logo-stretch img { height: 48px; max-height: 56px; }
            .sticky-logo img { height: 32px; max-height: 40px; }
            .sticky-title { font-size: 0.75rem; }
            .sticky-title i { display: none; }
            .sticky-actions .btn-sm { font-size: 0.65rem; padding: 2px 6px; }
            body { padding-top: 75px; }
        }
        html {
    scroll-behavior: smooth;
}
    </style>
</head>
<body>

<!-- ===== STICKY HEADER ===== -->
<div class="sticky-header hidden" id="stickyHeader">
    <div class="header-inner">
        <div class="sticky-logo">
            <img src="MDUE LOGO STRETCH.png" alt="MDUE Logo" />
        </div>
        <div class="sticky-title">
            <i class="bi bi-building"></i> EMPLOYEE LOANS
        </div>
        <div class="sticky-actions">
            <div class="theme-wrapper">
                <label class="theme-switch">
                    <input type="checkbox" class="theme-switch__checkbox" id="stickyThemeToggle">
                    <div class="theme-switch__container">
                        <div class="theme-switch__clouds"></div>
                        <div class="theme-switch__stars-container">
                            <svg viewBox="0 0 144 55" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="theme-switch__circle-container">
                            <div class="theme-switch__sun-moon-container">
                                <div class="theme-switch__moon">
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <a href="#formSection" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add
            </a>
            <a href="#tableSection" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-table"></i> View
            </a>
        </div>
    </div>
</div>

<div class="container-custom">

    <!-- ===== HEADER: LOGO + TITLE + THEME TOGGLE ===== -->
    <div class="text-center mb-4" id="topSection">
        <div class="logo-stretch mb-2">
            <img src="MDUE LOGO STRETCH.png" alt="MDUE Logo" class="img-fluid" />
        </div>
        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
            <h1 class="display-5 fw-semibold m-0">🏦 EMPLOYEE LOANS</h1>
            <div class="theme-wrapper">
                <span class="theme-label">🌙</span>
                <label class="theme-switch">
                    <input type="checkbox" class="theme-switch__checkbox" id="themeToggle">
                    <div class="theme-switch__container">
                        <div class="theme-switch__clouds"></div>
                        <div class="theme-switch__stars-container">
                            <svg viewBox="0 0 144 55" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="theme-switch__circle-container">
                            <div class="theme-switch__sun-moon-container">
                                <div class="theme-switch__moon">
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
                <span class="theme-label">☀️</span>
            </div>
        </div>
    </div>

    <!-- ===== FORM ===== -->
    <div class="card-custom p-4 p-md-5 mb-4" id="formSection">
        <h3 class="mb-4 text-center fw-semibold"><i class="bi bi-plus-circle"></i> ADD NEW EMPLOYEE LOAN</h3>
        <form action="submit_loan.php" method="post" onsubmit="return validateForm()">
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="emp_name" class="form-label fw-medium">Employee Name:</label>
                    <input type="text" class="form-control form-control-custom" id="emp_name" name="emp_name" required placeholder="Enter employee name" />
                </div>
                <div class="col-md-6">
                    <label for="dept" class="form-label fw-medium">Department:</label>
                    <input type="text" class="form-control form-control-custom" id="dept" name="dept" required placeholder="Enter department" />
                </div>
                <div class="col-md-6">
                    <label for="SSS_ID" class="form-label fw-medium">SSS ID (10 digits):</label>
                    <input type="text" class="form-control form-control-custom text-center" id="SSS_ID" name="SSS_ID" maxlength="10" pattern="[0-9]{10}" placeholder="Enter 10-digit SSS ID" oninput="limitSSS(this)" />
                    <span class="char-counter d-block text-center" id="sssCounter">0 / 10 digits</span>
                </div>
                <div class="col-md-6">
                    <label for="pagibig_id" class="form-label fw-medium">Pag-Ibig ID (12 digits):</label>
                    <input type="text" class="form-control form-control-custom text-center" id="pagibig_id" name="pagibig_id" maxlength="12" pattern="[0-9]{12}" placeholder="Enter 12-digit Pag-Ibig ID" oninput="limitPagIbig(this)" />
                    <span class="char-counter d-block text-center" id="pagibigCounter">0 / 12 digits</span>
                </div>
                <div class="col-md-6">
                    <label for="date_applied" class="form-label fw-medium">Date of Application:</label>
                    <input type="date" class="form-control form-control-custom" id="date_applied" name="date_applied" required />
                </div>
                <div class="col-md-6">
                    <label for="loan_type" class="form-label fw-medium">Type of Loan:</label>
                    <select class="form-select form-control-custom" id="loan_type" name="loan_type" required>
                        <option value="">Select Loan Type</option>
                        <option value="SSS">SSS</option>
                        <option value="PAG-IBIG">PAG-IBIG</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="loan_amount" class="form-label fw-medium">Amount of Loan:</label>
                    <input type="number" class="form-control form-control-custom" id="loan_amount" name="loan_amount" required step="0.01" min="0" placeholder="0.00" />
                </div>
                <div class="col-md-6">
                    <label for="total_deduction" class="form-label fw-medium">Total Amount of Deduction:</label>
                    <input type="number" class="form-control form-control-custom" id="total_deduction" name="total_deduction" required step="0.01" min="0" placeholder="0.00" />
                </div>
                <div class="col-md-6">
                    <label for="deduction_start" class="form-label fw-medium">Starting Date of Deduction:</label>
                    <input type="date" class="form-control form-control-custom" id="deduction_start" name="deduction_start" required />
                </div>
                <div class="col-md-6">
                    <label for="payment_1yr" class="form-label fw-medium">Payment Date:</label>
                    <input type="date" class="form-control form-control-custom" id="payment_1yr" name="payment_1yr" />
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label fw-medium">Status:</label>
                    <select class="form-select form-control-custom" id="status" name="status" required>
                        <option value="Unpaid">Unpaid</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="d-flex justify-content-center gap-3 mt-2">
                        <button type="submit" name="submit" class="btn btn-primary px-4 py-2">
                            <i class="bi bi-check2-circle me-1"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-secondary px-4 py-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ===== TABLE WITH ARCHIVE TABS ===== -->
    <h1 class="text-center display-6 fw-semibold my-4" id="tableSection">📋 EMPLOYEE LOANS DATA</h1>
    
            <!-- Tab Buttons -->
            <div class="tab-buttons">
                <a href="?tab=active#tableSection" class="btn <?php echo ($current_tab == 'active') ? 'btn-primary active-tab' : 'btn-outline-primary'; ?>">
                    <i class="bi bi-list-ul"></i> Active Loans
                </a>
                <a href="?tab=archived#tableSection" class="btn <?php echo ($current_tab == 'archived') ? 'btn-primary active-tab' : 'btn-outline-secondary'; ?>">
                    <i class="bi bi-archive"></i> Archived Loans
                </a>
                <a href="?tab=all#tableSection" class="btn <?php echo ($current_tab == 'all') ? 'btn-primary active-tab' : 'btn-outline-info'; ?>">
                    <i class="bi bi-database"></i> All Records
                </a>
            </div>

    <div class="card-custom p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-dark-mode table-hover align-middle mb-0" id="employeeTable">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>SSS ID</th>
                        <th>Pag-Ibig ID</th>
                        <th>Date Applied</th>
                        <th>Loan Type</th>
                        <th>Loan Amount</th>
                        <th>Total Deduction</th>
                        <th>Deduction Start</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Determine WHERE clause based on tab
                    if($current_tab == 'active') {
                        $where = "l.deleted_at IS NULL";
                    } elseif($current_tab == 'archived') {
                        $where = "l.deleted_at IS NOT NULL";
                    } else {
                        $where = "1=1";
                    }
                    
                    $sql = "SELECT l.*, e.emp_name, e.SSS_ID, e.pagibig_id 
                            FROM tbl_employee_loans l
                            LEFT JOIN tbl_employee e ON l.emp_id = e.emp_id
                            WHERE $where
                            ORDER BY l.loan_id DESC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $statusClass = ($row['status'] == 'Paid') ? 'badge-paid' : 'badge-unpaid';
                            $isArchived = $row['deleted_at'] !== null;
                            ?>
                            <tr data-loan-id="<?php echo $row['loan_id']; ?>">
                                <td><?php echo htmlspecialchars($row['loan_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['emp_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                <td><?php echo htmlspecialchars($row['SSS_ID']); ?></td>
                                <td><?php echo htmlspecialchars($row['pagibig_id']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['date_applied'])); ?></td>
                                <td><span class="badge bg-info"><?php echo htmlspecialchars($row['loan_type']); ?></span></td>
                                <td>₱<?php echo number_format($row['loan_amount'], 2); ?></td>
                                <td>₱<?php echo number_format($row['total_deduction'], 2); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['deduction_start'])); ?></td>
                                <td><?php echo $row['payment_1yr'] ? date('M d, Y', strtotime($row['payment_1yr'])) : 'N/A'; ?></td>
                                <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewModal"
                                            onclick="viewLoan(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <?php if(!$isArchived): ?>
                                        <a href="edit_loan.php?id=<?php echo $row['loan_id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning" onclick="archiveLoan(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-success" onclick="restoreLoan(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deletePermanently(<?php echo $row['loan_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['emp_name'])); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        $message = ($current_tab == 'archived') ? '📭 No archived records found' : '📭 No records found';
                        ?>
                        <tr>
                            <td colspan="13" class="text-center py-4"><?php echo $message; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== VIEW MODAL ===== -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="viewModalLabel">
                    <i class="bi bi-file-text"></i> Loan Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <span class="modal-detail-label">Loan ID:</span>
                            <span class="modal-detail-value" id="view_loan_id">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Employee Name:</span>
                            <span class="modal-detail-value" id="view_emp_name">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Department:</span>
                            <span class="modal-detail-value" id="view_dept">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">SSS ID:</span>
                            <span class="modal-detail-value" id="view_sss">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Pag-Ibig ID:</span>
                            <span class="modal-detail-value" id="view_pagibig">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Date Applied:</span>
                            <span class="modal-detail-value" id="view_date_applied">-</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <span class="modal-detail-label">Loan Type:</span>
                            <span class="modal-detail-value" id="view_loan_type">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Loan Amount:</span>
                            <span class="modal-detail-value" id="view_loan_amount">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Total Deduction:</span>
                            <span class="modal-detail-value" id="view_total_deduction">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Deduction Start:</span>
                            <span class="modal-detail-value" id="view_deduction_start">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Payment Date:</span>
                            <span class="modal-detail-value" id="view_payment_1yr">-</span>
                        </div>
                        <div class="detail-row">
                            <span class="modal-detail-label">Status:</span>
                            <span class="modal-detail-value" id="view_status">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <a href="#" id="viewEditBtn" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake for PDF export -->
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>   
<!-- DataTables Buttons HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let currentTab = '<?php echo $current_tab; ?>';

    // ===== DATATABLES INITIALIZATION =====
    $(document).ready(function() {
        $('#employeeTable').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [12] }
            ],
            dom: '<"d-flex flex-wrap justify-content-between align-items-center"<"export-buttons-wrapper"B><"dt-search"f>>rt<"d-flex flex-wrap justify-content-between align-items-center"lp>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    },
                    title: 'Employee Loans Data',
                    messageTop: 'Generated on: ' + new Date().toLocaleString()
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    },
                    title: 'Employee Loans Data',
                    messageTop: 'Generated on: ' + new Date().toLocaleString(),
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.styles.tableHeader.fillColor = '#3498db';
                        doc.styles.tableHeader.color = '#ffffff';


                        var tableNode = doc.content.find(function(item){
                            return item.table;
                        });
                        if (tableNode = doc.content.find(function(item) {
                            tableNode.table.widths = 
                                Array(tableNode.table.body[0].length).fill('*');
                        } 
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                    },
                    title: 'Employee Loans Data',
                    messageTop: 'Generated on: ' + new Date().toLocaleString()
                }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered from _MAX_ total entries)",
                zeroRecords: "No matching records found"
            }
        });
        $('.dt-search').addClass('d-flex justify-content-end');
    });

    // ===== VIEW LOAN FUNCTION =====
    function viewLoan(data) {
        document.getElementById('view_loan_id').textContent = data.loan_id;
        document.getElementById('view_emp_name').textContent = data.emp_name;
        document.getElementById('view_dept').textContent = data.dept;
        document.getElementById('view_sss').textContent = data.SSS_ID || 'N/A';
        document.getElementById('view_pagibig').textContent = data.pagibig_id || 'N/A';
        document.getElementById('view_date_applied').textContent = data.date_applied ? new Date(data.date_applied).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : 'N/A';
        document.getElementById('view_loan_type').textContent = data.loan_type;
        document.getElementById('view_loan_amount').textContent = '₱' + parseFloat(data.loan_amount).toFixed(2);
        document.getElementById('view_total_deduction').textContent = '₱' + parseFloat(data.total_deduction).toFixed(2);
        document.getElementById('view_deduction_start').textContent = data.deduction_start ? new Date(data.deduction_start).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : 'N/A';
        document.getElementById('view_payment_1yr').textContent = data.payment_1yr ? new Date(data.payment_1yr).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) : 'N/A';
        
        const statusBadge = data.status === 'Paid' ? 
            '<span class="badge-paid">Paid</span>' : 
            '<span class="badge-unpaid">Unpaid</span>';
        document.getElementById('view_status').innerHTML = statusBadge;
        document.getElementById('viewEditBtn').href = 'edit_loan.php?id=' + data.loan_id;
    }

// ===== ARCHIVE LOAN FUNCTION =====
function archiveLoan(loanId, empName) {
    Swal.fire({
        title: 'Archive Loan?',
        text: `Are you sure you want to archive this loan for ${empName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f39c12',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Archive it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'archive_loan.php',
                type: 'POST',
                data: { loan_id: loanId },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Archived!',
                            text: 'Loan record has been archived successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            window.location.href = window.location.pathname + '?tab=' + currentTab + '#tableSection';
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to archive loan record.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while archiving.'
                    });
                }
            });
        }
    });
}

// ===== RESTORE LOAN FUNCTION =====
function restoreLoan(loanId, empName) {
    Swal.fire({
        title: 'Restore Loan?',
        text: `Are you sure you want to restore this loan for ${empName}?`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#2ecc71',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Restore it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'restore_loan.php',
                type: 'POST',
                data: { loan_id: loanId },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Restored!',
                            text: 'Loan record has been restored successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            window.location.href = window.location.pathname + '?tab=' + currentTab + '#tableSection';
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to restore loan record.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while restoring.'
                    });
                }
            });
        }
    });
}

// ===== DELETE PERMANENTLY =====
function deletePermanently(loanId, empName) {
    Swal.fire({
        title: 'Delete Permanently?',
        text: `Are you sure you want to permanently delete this loan for ${empName}? This cannot be undone!`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'permanent_delete.php',
                type: 'POST',
                data: { loan_id: loanId },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Loan record has been permanently deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            window.location.href = window.location.pathname + '?tab=' + currentTab + '#tableSection';
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete loan record.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while deleting.'
                    });
                }
            });
        }
    });
}

    // ===== STICKY HEADER =====
    document.addEventListener('DOMContentLoaded', function() {
        const stickyHeader = document.getElementById('stickyHeader');
        let lastScrollY = window.scrollY;
        let ticking = false;

        function handleScroll() {
            const currentScrollY = window.scrollY;
            if (currentScrollY > 150) {
                stickyHeader.classList.remove('hidden');
            } else {
                stickyHeader.classList.add('hidden');
            }
            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });

        // ===== THEME TOGGLE =====
        const themeToggle = document.getElementById('themeToggle');
        const stickyThemeToggle = document.getElementById('stickyThemeToggle');

        function updateTheme(isDark) {
            if (isDark) {
                document.body.classList.add('dark-mode');
                themeToggle.checked = true;
                stickyThemeToggle.checked = true;
            } else {
                document.body.classList.remove('dark-mode');
                themeToggle.checked = false;
                stickyThemeToggle.checked = false;
            }
        }

        if (localStorage.getItem('darkMode') === 'enabled') {
            updateTheme(true);
        }

        themeToggle.addEventListener('change', function() {
            const isDark = this.checked;
            updateTheme(isDark);
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        });

        stickyThemeToggle.addEventListener('change', function() {
            const isDark = this.checked;
            updateTheme(isDark);
            localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
        });

        document.querySelectorAll('.sticky-actions a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = document.querySelector('.sticky-header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });

    // ===== SSS COUNTER =====
    function limitSSS(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        input.value = value;
        const counter = document.getElementById('sssCounter');
        counter.textContent = value.length + ' / 10 digits';
        if (value.length === 10) {
            counter.classList.add('limit-reached');
        } else {
            counter.classList.remove('limit-reached');
        }
    }

    // ===== PAG-IBIG COUNTER =====
    function limitPagIbig(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 12) value = value.slice(0, 12);
        input.value = value;
        const counter = document.getElementById('pagibigCounter');
        counter.textContent = value.length + ' / 12 digits';
        if (value.length === 12) {
            counter.classList.add('limit-reached');
        } else {
            counter.classList.remove('limit-reached');
        }
    }

    // ===== FORM VALIDATION =====
    function validateForm() {
        const empName = document.getElementById('emp_name');
        const dept = document.getElementById('dept');
        const dateApplied = document.getElementById('date_applied');
        const loanType = document.getElementById('loan_type');
        const loanAmount = document.getElementById('loan_amount');
        const totalDeduction = document.getElementById('total_deduction');
        const deductionStart = document.getElementById('deduction_start');

        if (empName.value.trim() === '') {
            alert('⚠️ Employee Name is required!');
            empName.focus();
            return false;
        }
        if (dept.value.trim() === '') {
            alert('⚠️ Department is required!');
            dept.focus();
            return false;
        }
        if (dateApplied.value === '') {
            alert('⚠️ Date of Application is required!');
            dateApplied.focus();
            return false;
        }
        if (loanType.value === '') {
            alert('⚠️ Loan Type is required!');
            loanType.focus();
            return false;
        }
        if (loanAmount.value === '' || parseFloat(loanAmount.value) <= 0) {
            alert('⚠️ Please enter a valid Loan Amount!');
            loanAmount.focus();
            return false;
        }
        if (totalDeduction.value === '' || parseFloat(totalDeduction.value) <= 0) {
            alert('⚠️ Please enter a valid Total Deduction!');
            totalDeduction.focus();
            return false;
        }
        if (deductionStart.value === '') {
            alert('⚠️ Starting Date of Deduction is required!');
            deductionStart.focus();
            return false;
        }
        return true;
    }
</script>
</body>
</html>
