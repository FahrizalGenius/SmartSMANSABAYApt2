<?php
// profile.php - Tampilan Read Only

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

$readonly = 'readonly';
$input_class = "w-full p-3 rounded-xl border-none text-base font-medium text-gray-800 bg-gray-50 focus:outline-none";

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>profile Biodata Diri</title>
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
<style>
    /* Style custom untuk menghilangkan outline input di read-only */
    .read-only-input:focus {
        box-shadow: none;
    }
</style>
</head>
<body class="bg-light-bg min-h-screen">

<div class="container mx-auto p-4 max-w-md">

    <header class="bg-white rounded-t-3xl shadow-lg flex justify-between items-center py-4 px-6 relative">
        <a href="#" class="flex items-center justify-center w-10 h-10 rounded-full border-2 border-accent-gold hover:bg-accent-gold hover:bg-opacity-10 transition duration-150">
            <svg class="w-5 h-5 text-accent-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        
        <h1 class="text-xl font-bold text-accent-gold mx-auto">Biodata Diri</h1>
        
        <a href="edit_profile.php" class="text-accent-gold font-bold text-lg hover:text-yellow-600">Edit</a>
    </header>
    
    <div class="bg-white p-6 rounded-b-3xl shadow-lg">
        
        <div class="flex justify-center mb-8">
            <img src="https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg" alt="profilee Avatar" class="w-28 h-28 rounded-full object-cover border-4 border-accent-gold shadow-md">
        </div>
        <div class="space-y-6">
            
            <h2 class="text-lg font-bold text-gray-700 border-b pb-1 mb-4">Info profile</h2>

            <?php
            // Fungsi untuk menampilkan field read-only
            function displayField($label, $value, $isPassword = false) {
                $type = $isPassword ? 'password' : 'text';
                $masking = $isPassword ? 'text-2xl tracking-widest' : '';
                
                echo "
                <div class='field'>
                    <label class='block text-sm font-medium text-gray-500 mb-1'>$label</label>
                    <input class='w-full p-3 rounded-xl border-none text-base font-medium text-gray-800 bg-gray-50 read-only-input $masking' 
                           type='$type' value='$value' readonly>
                </div>";
            }
            ?>

            <?php 
                displayField('Nama', $user['nama']); 
                displayField('Kelas', $user['kelas']); 
                displayField('BIO', $user['bio']); 
            ?>

            <h2 class="text-lg font-bold text-gray-700 border-b pt-4 pb-1 mb-4">Info Pribadi</h2>

            <?php 
                displayField('NIS', $user['nis']); 
                displayField('No HP/WA', $user['hp']); 
                displayField('Email', $user['email']); 
                displayField('Password', $user['password'], true); // Tampilkan sebagai password
            ?>
        </div>

    </div>

</div>

</body>
</html>
