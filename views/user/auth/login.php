<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

    <!-- Load Tailwind CSS dengan fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Cek apakah Tailwind berhasil dimuat
        setTimeout(() => {
            if (!window.tailwind) {
                console.warn('Tailwind CSS failed to load from CDN');
            }
        }, 2000);
    </script>

    <style>
        .gradient-purple {
            background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%);
        }
        
        /* Auto responsive container */
        .responsive-container {
            min-height: 100vh;
            min-height: 100dvh; /* Dynamic viewport height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            position: relative; /* Penting agar z-index animation area bekerja */
            overflow: hidden; /* Mencegah scrollbar jika animasi keluar batas */
        }

        /* Adjustments for various screens */
        @media (max-width: 320px) { .responsive-container { padding: 0.5rem; } }
        @media (min-width: 769px) { .responsive-container { padding: 2rem; } }
        
        /* Fix for high aspect ratio screens */
        @media (max-aspect-ratio: 9/16) {
            .responsive-container {
                justify-content: flex-start;
                padding-top: 15vh;
            }
        }
    </style>    
</head>

<body class="bg-white text-gray-800">

    <!-- 
      PENTING: 
      Pastikan path '../../../components/colaborator.php' sudah benar.
      Jika file tidak ditemukan, gunakan __DIR__ untuk path absolut.
    -->
    <?php 
        $colabPath = __DIR__ . '/../../../components/colaborator.php';
        if (file_exists($colabPath)) {
            include_once $colabPath; 
        }
    ?>
    
    <div class="responsive-container">
        <div class="w-full max-w-sm sm:max-w-md relative z-10">
            
            <?php 
                $animPath = __DIR__ . '/../../../components/animation-area.php';
                if (file_exists($animPath)) {
                    // FIX: Gunakan output buffering untuk membersihkan komentar Blade yang bocor
                    ob_start();
                    include_once $animPath;
                    $animContent = ob_get_clean();

                    // Hapus teks komentar {{-- --}} yang tidak sengaja tampil
                    $animContent = str_replace([
                        '{{-- Ini adalah kumpulan titik-titik animasi --}}',
                        '{{-- Setiap div adalah satu titik dengan posisi dan animasi unik --}}'
                    ], '', $animContent);

                    echo $animContent;
                }
            ?>

            <!-- Login Form Section -->
            <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-sm border border-white/50">
                <form action="login_process.php" method="POST" class="space-y-4">
                    <!-- CSRF Token Check -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            placeholder="Email" 
                            class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-700 text-sm sm:text-base transition-all"
                            required
                        >
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input 
                            type="password" 
                            id="password"
                            name="password" 
                            placeholder="Password" 
                            class="w-full px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-700 text-sm sm:text-base transition-all"
                            required
                        >
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-center space-y-1 pt-2">
                        <p class="text-gray-400 text-xs sm:text-sm">atau</p>
                        <a href="password_reset.php" class="text-blue-500 hover:text-blue-600 text-xs sm:text-sm font-medium transition-colors">
                            Reset akun lain?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full gradient-purple text-white font-bold py-3.5 rounded-full shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:opacity-95 transform hover:-translate-y-0.5 transition-all duration-200 mt-4 text-sm sm:text-base"
                    >
                        Masuk
                    </button>

                    <!-- Register Link -->
                    <button 
                        type="button"
                        onclick="window.location.href='register.php'"
                        class="w-full bg-white border-2 border-purple-100 text-purple-600 font-semibold py-3 rounded-full hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 text-sm sm:text-base"
                    >
                        Belum ada akun? Daftar dulu
                    </button>
                </form>
            </div>
        </div>
    </div> 
</body>
</html>