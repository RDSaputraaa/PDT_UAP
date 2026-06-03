<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - AmanParkir</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .setup-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .setup-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .setup-header p {
            color: var(--text-light);
        }
        .setup-steps {
            margin: 30px 0;
        }
        .setup-step {
            padding: 15px;
            border-left: 4px solid var(--primary-color);
            background: var(--light-bg);
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .setup-step h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .setup-step p {
            margin: 0;
            font-size: 14px;
            color: var(--text-dark);
        }
        .code {
            background: #1f2937;
            color: #10b981;
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            overflow-x: auto;
            font-size: 12px;
        }
        .success-message {
            padding: 15px;
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--success-color);
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .form-setup {
            margin: 20px 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-setup {
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-setup:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-parking"></i> AmanParkir</h1>
            <p>Sistem Manajemen Tempat Parkir FMIPA</p>
            <hr style="margin: 20px 0; border: none; border-top: 2px solid var(--border-color);">
        </div>

        <?php
        require_once dirname(__DIR__) . '/config/database.php';

        $is_setup = false;
        try {
            $result = $conn->query("SELECT 1 FROM parking_areas LIMIT 1");
            if ($result) {
                $is_setup = true;
            }
        } catch (Exception $e) {
            $is_setup = false;
        }

        if ($is_setup):
        ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <strong>Database sudah dikonfigurasi!</strong>
                <p style="margin: 10px 0 0 0; font-size: 14px;">Sistem AmanParkir sudah siap digunakan. Klik tombol di bawah untuk melanjutkan ke dashboard.</p>
            </div>

            <div style="text-align: center;">
                <a href="index.php" class="btn btn-primary" style="display: inline-block; padding: 12px 30px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i> Menuju Dashboard
                </a>
            </div>

        <?php else: ?>
            <div class="setup-steps">
                <h2 style="margin-top: 0;">Langkah Instalasi</h2>

                <div class="setup-step">
                    <h3><i class="fas fa-database"></i> 1. Import Database</h3>
                    <p>Buka phpMyAdmin dan import file parkir_pdt.sql dari folder config/</p>
                    <p>Atau jalankan perintah di bawah di terminal MySQL:</p>
                    <div class="code">mysql -u root -p < config/parkir_pdt.sql</div>
                </div>

                <div class="setup-step">
                    <h3><i class="fas fa-cogs"></i> 2. Konfigurasi Database</h3>
                    <p>Periksa file config/database.php untuk memastikan kredensial database benar:</p>
                    <div class="code">
                        DB_HOST: localhost<br>
                        DB_USER: root<br>
                        DB_PASS: (kosong)<br>
                        DB_NAME: amanparkir
                    </div>
                </div>

                <div class="setup-step">
                    <h3><i class="fas fa-check-square"></i> 3. Verifikasi Koneksi</h3>
                    <p>Sistem akan secara otomatis mendeteksi ketika database sudah siap. Refresh halaman ini untuk melihat status terbaru.</p>
                </div>
            </div>

            <form method="GET" action="" style="text-align: center;">
                <button type="submit" class="btn-setup" style="margin-top: 20px;">
                    <i class="fas fa-sync-alt"></i> Cek Koneksi Database
                </button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>
