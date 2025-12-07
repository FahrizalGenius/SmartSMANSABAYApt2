<!-- // kalau edit jangan lupa tambahkan?php dan di akhiannya --> -->
// process/user/auth/register.php
<?php  
session_start();

// Include database connection
require_once '../../../config/koneksi.php';

// Function untuk sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function untuk validate CSRF token
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Function untuk generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function untuk validate NIS format (contoh: hanya angka, 8-20 karakter)
function validateNIS($nis) {
    return preg_match('/^[0-9]{8,20}$/', $nis);
}

// Function untuk validate phone number
function validatePhone($phone) {
    // Indonesia phone format: 08xx atau +628xx atau 628xx
    return preg_match('/^(\+628|628|08)[0-9]{8,12}$/', $phone);
}

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method';
    header('Location: ../../../views/user/auth/register.php');
    exit;
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Invalid CSRF token';
    header('Location: ../../../views/user/auth/register.php');
    exit;
}

// Ambil dan sanitize input
$email = sanitizeInput($_POST['email'] ?? '');
$nis = sanitizeInput($_POST['NIS'] ?? '');
$no_telp = sanitizeInput($_POST['no_telp'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirmation = $_POST['password_confirmation'] ?? '';

// Validation
$errors = [];

// Validate email
if (empty($email)) {
    $errors['email'] = 'Email wajib diisi';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Format email tidak valid';
}

// Validate NIS
if (empty($nis)) {
    $errors['NIS'] = 'NIS wajib diisi';
} elseif (!validateNIS($nis)) {
    $errors['NIS'] = 'NIS harus berupa angka 8-20 digit';
}

// Validate phone number
if (empty($no_telp)) {
    $errors['no_telp'] = 'Nomor telepon wajib diisi';
} elseif (!validatePhone($no_telp)) {
    $errors['no_telp'] = 'Format nomor telepon tidak valid (contoh: 08123456789)';
}

// Validate password
if (empty($password)) {
    $errors['password'] = 'Password wajib diisi';
} elseif (strlen($password) < 6) {
    $errors['password'] = 'Password minimal 6 karakter';
} elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
    $errors['password'] = 'Password harus mengandung huruf besar, huruf kecil, dan angka';
}

// Validate password confirmation
if (empty($password_confirmation)) {
    $errors['password_confirmation'] = 'Konfirmasi password wajib diisi';
} elseif ($password !== $password_confirmation) {
    $errors['password_confirmation'] = 'Konfirmasi password tidak cocok';
}

// Jika ada error validasi, redirect kembali
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = [
        'email' => $email,
        'NIS' => $nis,
        'no_telp' => $no_telp
    ];
    header('Location: ../../../views/user/auth/register.php');
    exit;
}

try {
    // Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors['email'] = 'Email sudah terdaftar, gunakan email lain';
    }

    // Cek apakah NIS sudah terdaftar
    $stmt = $pdo->prepare("SELECT id_user FROM users WHERE nis = ? LIMIT 1");
    $stmt->execute([$nis]);
    if ($stmt->fetch()) {
        $errors['NIS'] = 'NIS sudah terdaftar';
    }

    // Jika ada error duplicate, redirect kembali
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = [
            'email' => $email,
            'NIS' => $nis,
            'no_telp' => $no_telp
        ];
        header('Location: ../../../views/user/auth/register.php');
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate nama default dari email (bisa diubah nanti)
    $nama = explode('@', $email)[0];
    $nama = ucfirst($nama);

    // Insert user baru ke database
    $stmt = $pdo->prepare("
        INSERT INTO users (nis, nama, email, password, role, created_at) 
        VALUES (?, ?, ?, ?, 'siswa', CURRENT_TIMESTAMP)
    ");
    
    $result = $stmt->execute([$nis, $nama, $email, $hashedPassword]);

    if ($result) {
        // Ambil ID user yang baru dibuat
        $newUserId = $pdo->lastInsertId();

        // Optional: Simpan nomor telepon ke tabel terpisah jika ada
        // $phoneStmt = $pdo->prepare("INSERT INTO user_phones (user_id, phone_number) VALUES (?, ?)");
        // $phoneStmt->execute([$newUserId, $no_telp]);

        // Clear any existing error/old data
        unset($_SESSION['errors']);
        unset($_SESSION['error']);
        unset($_SESSION['old_data']);

        // Set success message
        $_SESSION['success'] = 'Akun berhasil dibuat! Silakan login dengan akun Anda.';
        
        // Optional: Auto login user (hapus jika tidak diinginkan)
        /*
        $_SESSION['user_id'] = $newUserId;
        $_SESSION['user_nis'] = $nis;
        $_SESSION['user_nama'] = $nama;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'siswa';
        $_SESSION['is_logged_in'] = true;
        $_SESSION['login_time'] = time();
        */

        // Redirect ke halaman login
        header('Location: ../../../views/user/auth/login.php');
        exit;

    } else {
        throw new Exception('Gagal menyimpan data user');
    }

} catch (PDOException $e) {
    // Log error ke file
    error_log("Register error: " . $e->getMessage());
    
    // Cek apakah error karena duplicate key
    if ($e->getCode() == 23000) {
        $_SESSION['error'] = 'Email atau NIS sudah terdaftar';
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan pada server. Silakan coba lagi.';
    }
    
    $_SESSION['old_data'] = [
        'email' => $email,
        'NIS' => $nis,
        'no_telp' => $no_telp
    ];
    
    header('Location: ../../../views/user/auth/register.php');
    exit;

} catch (Exception $e) {
    error_log("Register error: " . $e->getMessage());
    
    $_SESSION['error'] = 'Terjadi kesalahan. Silakan coba lagi.';
    $_SESSION['old_data'] = [
        'email' => $email,
        'NIS' => $nis,
        'no_telp' => $no_telp
    ];
    
    header('Location: ../../../views/user/auth/register.php');
    exit;
}
?>