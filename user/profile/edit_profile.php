<?php
// edit_profile.php - Formulir Edit

// --- DATA USER ---
$user = [
    "nama"      => "Senaif Arifin",
    "kelas"     => "XII MIPA 2",
    "bio"       => "tetap sakit walau tersakiti",
    "nis"       => "***7",
    "hp"        => "08********650",
    "email"     => "ar********@gmail.com",
    "password"  => "************"
];

// Logika penyimpanan (Simulasi)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logika penyimpanan data ke database
    
    // Setelah menyimpan, arahkan kembali ke halaman profile
    header('Location: profile.php');
    exit;
}

$input_class = "w-full p-3 rounded-xl border border-gray-200 text-base font-medium text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-accent-gold focus:border-accent-gold transition duration-150";

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Biodata Diri</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'accent-gold': '#FFC300', // Kuning/emas yang cerah
                    'light-bg': '#f9f9f9',
                }
            }
        }
    }
</script>
</head>
<body class="bg-light-bg min-h-screen">

<div class="container mx-auto p-4 max-w-md">

    <header class="bg-white rounded-t-3xl shadow-lg flex justify-between items-center py-4 px-6 relative">
        <a href="profile.php" class="flex items-center justify-center w-10 h-10 rounded-full border-2 border-accent-gold hover:bg-accent-gold hover:bg-opacity-10 transition duration-150">
            <svg class="w-5 h-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        
        <h1 class="text-xl font-bold text-accent-gold mx-auto">Biodata Diri</h1>
        
        <button form="editForm" type="submit" class="text-accent-gold font-bold text-lg hover:text-yellow-600">Simpan</button>
    </header>
    
    <div class="bg-white p-6 rounded-b-3xl shadow-lg">
        
        <div class="flex justify-center mb-8">
            <img src="https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg" alt="profilee Avatar" class="w-28 h-28 rounded-full object-cover border-4 border-accent-gold shadow-md cursor-pointer">
        </div>

        <form id="editForm" method="POST" action="edit_profile.php" class="space-y-6">

            <h2 class="text-lg font-bold text-gray-700 border-b pb-1 mb-4">Info profile</h2>
            
            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                <input class="<?= $input_class ?>" type="text" name="nama" value="<?= $user['nama'] ?>">
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">Kelas</label>
                <input class="<?= $input_class ?>" type="text" name="kelas" value="<?= $user['kelas'] ?>">
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">BIO</label>
                <input class="<?= $input_class ?>" type="text" name="bio" value="<?= $user['bio'] ?>">
            </div>

            <h2 class="text-lg font-bold text-gray-700 border-b pt-4 pb-1 mb-4">Info Pribadi</h2>
            
            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">NIS</label>
                <input class="<?= $input_class ?>" type="text" name="nis" value="<?= $user['nis'] ?>">
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">No HP/WA</label>
                <input class="<?= $input_class ?>" type="text" name="hp" value="<?= $user['hp'] ?>">
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                <input class="<?= $input_class ?>" type="email" name="email" value="<?= $user['email'] ?>">
            </div>

            <div class="field">
                <label class="block text-sm font-medium text-gray-500 mb-1">Password</label>
                <input class="<?= $input_class ?> tracking-widest" type="password" name="password" placeholder="Isi jika ingin ganti password" autocomplete="new-password">
            </div>

            <button type="submit" class="hidden"></button> 
        </form>
    </div>

</div>

</body>
</html>
