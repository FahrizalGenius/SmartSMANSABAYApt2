<?php
session_start();

// --- [DATA DUMMY] ---
if (!isset($user)) {
    $user = (object) [
        'name' => 'Seinal Arifin',
        'email' => 'Seinal@arosbaya.sch.id',
        'phone' => '081234567890',
        'kelas' => 'XII MIPA 1',           
        'bio'   => 'Hobi membaca dan coding' 
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Kantin Smart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-md mx-auto bg-white min-h-screen relative shadow-2xl overflow-hidden flex flex-col">
        
        <div class="bg-green-600 p-6 pt-10 rounded-b-[40px] shadow-lg relative z-10 text-center">
            <a href="../dasboard/home.php" class="absolute top-6 left-6 text-white/80 hover:text-white transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>

            <div class="relative inline-block mb-3">
                <div class="w-24 h-24 rounded-full border-4 border-white/30 bg-white p-1 mx-auto shadow-xl">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->name) ?>&background=random&size=128" class="w-full h-full rounded-full object-cover">
                </div>
                <div class="absolute bottom-0 right-0 bg-yellow-400 text-white w-8 h-8 rounded-full flex items-center justify-center border-2 border-green-600 shadow-sm">
                    <i class="fas fa-pen text-xs text-green-800"></i>
                </div>
            </div>
            
            <h2 class="text-white text-xl font-bold"><?= htmlspecialchars($user->name) ?></h2>
            <p class="text-green-100 text-sm"><?= htmlspecialchars($user->kelas ?? 'Siswa') ?></p>
        </div>

        <div class="flex-1 px-6 py-8 overflow-y-auto pb-24">
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="../../../process/user/update_profile.php" method="POST">
                <input type="hidden" name="_method" value="PUT">
                
                <h3 class="font-bold text-gray-800 mb-4 text-sm">Informasi Pribadi</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            <input type="text" name="name" value="<?= htmlspecialchars($user->name) ?>" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="Nama Lengkap" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Kelas</label>
                        <div class="relative">
                            <i class="fas fa-graduation-cap absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            <input type="text" name="kelas" value="<?= htmlspecialchars($user->kelas ?? '') ?>" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="Contoh: XII IPA 1">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Bio Singkat</label>
                        <div class="relative">
                            <i class="fas fa-quote-left absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            <textarea name="bio" rows="2" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="Tulis sedikit tentang dirimu..."><?= htmlspecialchars($user->bio ?? '') ?></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Email Sekolah</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            <input type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="email@sekolah.sch.id" required>
                        </div>
                    </div>

                    <!-- INPUT PASSWORD DENGAN TOGGLE MATA -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Password Baru</label>
                        <!-- x-data untuk mengontrol state show/hide password -->
                        <div class="relative" x-data="{ show: false }">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            
                            <!-- Atribut type berubah dinamis berdasarkan state 'show' -->
                            <input :type="show ? 'text' : 'password'" name="password" class="w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="Kosongkan jika tidak diubah">
                            
                            <!-- Tombol Mata -->
                            <button type="button" @click="show = !show" class="absolute right-4 top-3.5 text-gray-400 hover:text-green-600 focus:outline-none cursor-pointer">
                                <!-- Ikon berubah dinamis: mata biasa vs mata dicoret -->
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 ml-1">Nomor HP / WA</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                            <input type="text" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:bg-white transition" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <button type="submit" name="update_profile" class="w-full mt-6 bg-green-600 text-white py-3.5 rounded-xl font-bold shadow-lg shadow-green-600/20 hover:bg-green-700 active:scale-[0.98] transition">
                    Simpan Perubahan
                </button>
            </form>

            <hr class="border-dashed border-gray-200 my-8">

            <h3 class="font-bold text-red-500 mb-4 text-sm">Zona Bahaya</h3>
            
            <div class="space-y-3">
                <form action="../../user/auth/login.php" method="POST">
                    <button type="submit" class="w-full bg-white border border-red-100 text-red-500 py-3 rounded-xl font-semibold hover:bg-red-50 transition flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Keluar (Logout)
                    </button>
                </form>

                <div x-data="{ open: false }">
                    <button @click="open = true" type="button" class="w-full text-red-400 text-xs py-2 hover:underline">
                        Hapus Akun Saya Permanen
                    </button>

                    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>
                        <div class="bg-white rounded-2xl p-6 w-full max-w-xs relative z-10 text-center shadow-2xl">
                            <div class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 mb-1">Hapus Akun?</h3>
                            <p class="text-xs text-gray-500 mb-5">Semua data pesanan dan saldo kamu akan hilang. Yakin?</p>
                            
                            <div class="flex gap-3">
                                <button @click="open = false" class="flex-1 py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">Batal</button>
                                
                                <form action="../../../process/user/hapus_akun.php" method="POST" class="flex-1">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="delete_account" class="w-full py-2 bg-red-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-red-200">Ya, Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>