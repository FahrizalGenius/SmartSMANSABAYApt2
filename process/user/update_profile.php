<?php
session_start();

// 1. Hubungkan ke Database
// Lokasi: process/user/update_profile.php -> Mundur 2 langkah ke root -> config/koneksi.php
require_once '../../config/koneksi.php'; 

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika belum login
    header("Location: ../../views/user/auth/login.php");
    exit();
}

// Cek apakah tombol submit ditekan
if (isset($_POST['update_profile'])) {

    // 2. Ambil data dari Form & Amankan Input
    $id    = $_SESSION['user_id'];
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $bio   = mysqli_real_escape_string($conn, $_POST['bio']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass  = $_POST['password']; // Password tidak perlu escape string karena akan di-hash

    // 3. Logika Update Password
    if (!empty($pass)) {
        // A. JIKA PASSWORD DIISI: Update data TERMASUK password
        
        // Hash password baru agar aman (Default: Bcrypt)
        $password_hash = password_hash($pass, PASSWORD_DEFAULT);
        
        $query = "UPDATE users SET 
                    name = ?, 
                    kelas = ?, 
                    bio = ?, 
                    email = ?, 
                    phone = ?, 
                    password = ? 
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        // "ssssssi" artinya: String, String, String, String, String, String, Integer
        $stmt->bind_param("ssssssi", $name, $kelas, $bio, $email, $phone, $password_hash, $id);
        
    } else {
        // B. JIKA PASSWORD KOSONG: Update data KECUALI password
        
        $query = "UPDATE users SET 
                    name = ?, 
                    kelas = ?, 
                    bio = ?, 
                    email = ?, 
                    phone = ? 
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        // "sssssi" artinya: String, String, String, String, String, Integer
        $stmt->bind_param("sssssi", $name, $kelas, $bio, $email, $phone, $id);
    }

    // 4. Eksekusi Query
    if ($stmt->execute()) {
        
        // 5. Update Data SESSION
        // Ini PENTING agar tampilan (nama/kelas) di header langsung berubah tanpa logout
        
        if (isset($_SESSION['user'])) {
            // Cek apakah session disimpan sebagai Object atau Array
            if (is_object($_SESSION['user'])) {
                $_SESSION['user']->name  = $name;
                $_SESSION['user']->email = $email;
                $_SESSION['user']->phone = $phone;
                $_SESSION['user']->kelas = $kelas;
                $_SESSION['user']->bio   = $bio;
            } else {
                $_SESSION['user']['name']  = $name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['kelas'] = $kelas;
                $_SESSION['user']['bio']   = $bio;
            }
        }

        // Set pesan sukses (akan muncul di kotak hijau di halaman profil)
        $_SESSION['success'] = "Profil berhasil diperbarui!";
        
        // Redirect kembali ke halaman profil
        header("Location: ../../views/user/profile/profile.php");
        exit();

    } else {
        // Jika gagal query (misal email duplikat)
        $_SESSION['error'] = "Gagal memperbarui profil: " . $conn->error;
        header("Location: ../../views/user/profile/profile.php");
        exit();
    }

} else {
    // Jika file diakses langsung tanpa menekan tombol simpan
    header("Location: ../../views/user/profile/profile.php");
    exit();
}
?>