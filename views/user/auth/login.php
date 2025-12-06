<?php
// filepath: /opt/lampp/htdocs/SmartSMANSABAYApt2/views/user/auth/login.php
?>
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
        .animate-bounce.delay-150 {
            animation-delay: 150ms;
        }
        
        /* Auto responsive untuk semua ukuran layar */
        .responsive-container {
            min-height: 100vh;
            min-height: 100dvh; /* Dynamic viewport height untuk mobile */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        
        /* Breakpoints untuk berbagai ukuran HP */
        @media (max-width: 320px) {
            /* HP kecil (iPhone SE, dll) */
            .responsive-container {
                padding: 0.5rem;
            }
        }
        
        @media (min-width: 321px) and (max-width: 480px) {
            /* HP sedang */
            .responsive-container {
                padding: 1rem;
            }
        }
        
        @media (min-width: 481px) and (max-width: 768px) {
            /* HP besar / tablet kecil */
            .responsive-container {
                padding: 1.5rem;
            }
        }
        
        @media (min-width: 769px) {
            /* Desktop */
            .responsive-container {
                padding: 2rem;
            }
        }
        
        /* Untuk HP dengan aspect ratio tinggi */
        @media (max-aspect-ratio: 9/16) {
            .responsive-container {
                justify-content: flex-start;
                padding-top: 15vh;
            }
        }
    </style>    
</head>

<body class="bg-white">
    <div class="responsive-container">
        <div class="w-full max-w-sm sm:max-w-md">
            <?php 
            // Include animation area
            if (file_exists('../components/animation-area.php')) {
                include_once '../components/animation-area.php'; 
            } else {
                // Fallback animation
                echo '<div class="text-center mb-8">
                        <div class="animate-bounce text-6xl">🔐</div>
                        <h1 class="text-2xl font-bold text-gray-800 mt-4">Login</h1>
                        <p class="text-gray-600 text-sm mt-2">Smart SMANSABAYA</p>
                      </div>';
            }
            ?>

            <!-- Login Form -->
            <form action="../../../process/user/auth/login.php" method="POST" class="space-y-4 mt-8">
                <!-- CSRF Protection -->
                <?php 
                session_start();
                if (!isset($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                ?>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Email Input -->
                <div>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="Email" 
                        class="w-full px-4 py-3 bg-gray-100 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-700 text-sm sm:text-base"
                        value="<?php echo isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : ''; ?>"
                        required
                    >
                    <?php if (isset($_SESSION['errors']['email'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $_SESSION['errors']['email']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password Input -->
                <div>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Password" 
                        class="w-full px-4 py-3 bg-gray-100 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-purple-500 text-gray-700 text-sm sm:text-base"
                        required
                    >
                    <?php if (isset($_SESSION['errors']['password'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo $_SESSION['errors']['password']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Error Message -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Forgot Password & Reset Link -->
                <div class="text-center space-y-1">
                    <p class="text-gray-400 text-xs sm:text-sm">atau</p>
                    <a href="password_reset.php" class="text-blue-500 text-xs sm:text-sm">Reset akun lain?</a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full gradient-purple text-white font-semibold py-3 rounded-full hover:opacity-90 transition-opacity mt-6 text-sm sm:text-base"
                >
                    Masuk
                </button>

                <!-- Register Link -->
                <button 
                    type="button"
                    onclick="window.location.href='register.php'"
                    class="w-full bg-white border-2 border-purple-500 text-purple-500 font-semibold py-3 rounded-full hover:bg-purple-50 transition-colors text-sm sm:text-base"
                >
                    Belum ada akun? Daftar dulu
                </button>
            </form>

            <?php
            // Clear old input data
            if (isset($_SESSION['old_email'])) {
                unset($_SESSION['old_email']);
            }
            if (isset($_SESSION['errors'])) {
                unset($_SESSION['errors']);
            }
            ?>
        </div>
    </div> 
</body>
</html>