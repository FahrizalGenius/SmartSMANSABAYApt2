<?php
session_start();

// --- LOGIKA CHAT SEDERHANA MENGGUNAKAN SESSION ---

// Inisialisasi chat history jika belum ada di session
if (!isset($_SESSION['chat_messages'])) {
    $_SESSION['chat_messages'] = [
        [
            'sender' => 'admin',
            'text' => 'Halo! Ada yang bisa kami bantu terkait pesanan makanan kantin?',
            'time' => date('H:i')
        ]
    ];
}

// Handle saat user mengirim pesan
if (isset($_POST['send_message']) && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);
    
    // 1. Simpan pesan user
    $_SESSION['chat_messages'][] = [
        'sender' => 'user',
        'text' => $message,
        'time' => date('H:i')
    ];
    
    // 2. Simulasi balasan admin (Logic sederhana untuk respon otomatis)
    $reply = "";
    $msg_lower = strtolower($message);
    
    if (strpos($msg_lower, 'stok') !== false || strpos($msg_lower, 'habis') !== false) {
        $reply = "Untuk stok menu silakan cek di halaman beranda ya kak. Jika tidak muncul berarti sedang kosong.";
    } elseif (strpos($msg_lower, 'bayar') !== false || strpos($msg_lower, 'saldo') !== false) {
        $reply = "Pembayaran bisa menggunakan saldo Smart Kantin atau tunai langsung di kasir saat pengambilan.";
    } elseif (strpos($msg_lower, 'halo') !== false || strpos($msg_lower, 'pagi') !== false || strpos($msg_lower, 'siang') !== false) {
        $reply = "Halo juga! Selamat datang di layanan chat kantin.";
    } elseif (strpos($msg_lower, 'kapan') !== false || strpos($msg_lower, 'buka') !== false) {
        $reply = "Kantin buka setiap jam istirahat sekolah (09.30 - 10.00 & 12.00 - 13.00).";
    } else {
        // Balasan default
        $reply = "Terima kasih infonya, admin akan segera mengecek pesan kakak.";
    }
    
    if (!empty($reply)) {
        $_SESSION['chat_messages'][] = [
            'sender' => 'admin',
            'text' => $reply,
            'time' => date('H:i')
        ];
    }
    
    // Redirect agar form tidak tersubmit ulang saat refresh (PRG Pattern)
    header("Location: chat.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chat Admin - Smart SMANSABAYA</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Menggunakan style dasar yang sama dengan home.php untuk konsistensi */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background-color: #F8F9FD; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        
        /* HEADER */
        .chat-header {
            background-color: #00A859; padding: 15px 20px;
            display: flex; align-items: center; gap: 15px;
            color: white; box-shadow: 0 4px 15px rgba(0, 168, 89, 0.15);
            z-index: 10;
        }
        .btn-back { color: white; font-size: 20px; cursor: pointer; }
        .admin-avatar {
            width: 45px; height: 45px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #00A859; font-size: 20px; border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        .admin-info h3 { font-size: 16px; font-weight: 600; line-height: 1.2; margin-bottom: 2px;}
        .admin-status { font-size: 12px; opacity: 0.9; display: flex; align-items: center; gap: 5px; }
        .status-dot { width: 8px; height: 8px; background-color: #00ff00; border-radius: 50%; display: inline-block; box-shadow: 0 0 5px #00ff00; }

        /* CHAT AREA */
        .chat-container {
            flex: 1; padding: 20px; overflow-y: auto;
            display: flex; flex-direction: column; gap: 15px;
            background-color: #F8F9FD;
            scroll-behavior: smooth;
            padding-bottom: 20px;
        }
        
        .message-wrapper {
            display: flex; flex-direction: column; max-width: 80%;
            animation: fadeIn 0.3s ease;
        }
        .message-wrapper.user { align-self: flex-end; align-items: flex-end; }
        .message-wrapper.admin { align-self: flex-start; align-items: flex-start; }

        .message-bubble {
            padding: 12px 16px; border-radius: 16px; font-size: 14px; line-height: 1.5;
            position: relative; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .user .message-bubble {
            background-color: #00A859; color: white;
            border-bottom-right-radius: 4px;
        }
        
        .admin .message-bubble {
            background-color: white; color: #333;
            border-bottom-left-radius: 4px;
            border: 1px solid #eee;
        }
        
        .message-time {
            font-size: 10px; color: #999; margin-top: 5px; margin-left: 5px; margin-right: 5px;
        }
        
        /* FOOTER INPUT */
        .chat-input-area {
            background-color: white; padding: 15px 20px;
            display: flex; align-items: center; gap: 10px;
            border-top: 1px solid #eee;
        }
        .input-wrapper {
            flex: 1; position: relative;
        }
        .chat-input {
            width: 100%; padding: 12px 20px;
            border-radius: 25px; border: 1px solid #eee;
            background-color: #f5f5f5; outline: none;
            font-size: 14px; transition: all 0.3s;
        }
        .chat-input:focus { background-color: white; border-color: #00A859; }
        
        .btn-send {
            width: 45px; height: 45px; background-color: #00A859;
            border-radius: 50%; color: white; display: flex;
            align-items: center; justify-content: center; font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 168, 89, 0.3);
            transition: transform 0.1s; cursor: pointer;
            flex-shrink: 0;
        }
        .btn-send:active { transform: scale(0.95); }
        
        /* DATE DIVIDER */
        .date-divider {
            text-align: center; margin: 10px 0; position: relative;
        }
        .date-divider span {
            background-color: rgba(0,0,0,0.05); color: #888;
            padding: 4px 12px; border-radius: 10px; font-size: 11px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* CUSTOM SCROLLBAR */
        .chat-container::-webkit-scrollbar { width: 4px; }
        .chat-container::-webkit-scrollbar-thumb { background-color: #ddd; border-radius: 4px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="chat-header">
        <a href="home.php" class="btn-back"><i class="fas fa-arrow-left"></i></a>
        <div class="admin-avatar">
            <i class="fas fa-headset"></i>
        </div>
        <div class="admin-info">
            <h3>Admin Kantin</h3>
            <div class="admin-status">
                <span class="status-dot"></span> Online
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-container" id="chatContainer">
        
        <div class="date-divider">
            <span>Hari ini</span>
        </div>

        <?php foreach ($_SESSION['chat_messages'] as $msg): ?>
            <div class="message-wrapper <?= $msg['sender'] ?>">
                <div class="message-bubble">
                    <?= htmlspecialchars($msg['text']) ?>
                </div>
                <span class="message-time">
                    <?= $msg['sender'] == 'user' ? '<i class="fas fa-check-double" style="font-size:8px; margin-right:3px;"></i>' : '' ?>
                    <?= $msg['time'] ?>
                </span>
            </div>
        <?php endforeach; ?>

    </div>

    <!-- Input Area -->
    <form method="POST" action="" class="chat-input-area">
        <div class="input-wrapper">
            <input type="text" name="message" class="chat-input" placeholder="Tulis pesan..." autocomplete="off" required>
        </div>
        <button type="submit" name="send_message" class="btn-send">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>

    <script>
        // Auto scroll ke bawah saat halaman dimuat
        window.onload = function() {
            var chatContainer = document.getElementById("chatContainer");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    </script>

</body>
</html>