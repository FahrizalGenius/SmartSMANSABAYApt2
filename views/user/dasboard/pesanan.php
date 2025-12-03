<?php
session_start();

// --- DATA DUMMY---
$riwayat_pesanan = [
    [
        'id_order' => '#ORD-001',
        'tanggal' => '25 Nov 2025, 10:30',
        'status' => 'Diproses', // Status: Menunggu/Diproses/Siap Diambil/Selesai
        'total' => 15500,
        'menu' => ['Nasi Goreng Spesial', 'Es Teh Manis'],
    ],
    [
        'id_order' => '#ORD-005',
        'tanggal' => '24 Nov 2025, 09:15',
        'status' => 'Siap Diambil',
        'total' => 12000,
        'menu' => ['Mie Ayam Bakso'],
    ],
    [
        'id_order' => '#ORD-008',
        'tanggal' => '23 Nov 2025, 12:00',
        'status' => 'Selesai',
        'total' => 25000,
        'menu' => ['Ayam Geprek', 'Jus Alpukat', 'Kerupuk'],
    ],
    [
        'id_order' => '#ORD-012',
        'tanggal' => '20 Nov 2025, 08:45',
        'status' => 'Dibatalkan',
        'total' => 10000,
        'menu' => ['Roti Bakar Coklat'],
    ]
];

// Helper Warna Status
function getStatusColor($status) {
    switch ($status) {
        case 'Selesai': return ['bg' => '#E8F5E9', 'text' => '#2E7D32', 'icon' => 'fa-check-circle']; // Hijau
        case 'Siap Diambil': return ['bg' => '#E3F2FD', 'text' => '#1565C0', 'icon' => 'fa-bell']; // Biru
        case 'Diproses': return ['bg' => '#FFF3E0', 'text' => '#EF6C00', 'icon' => 'fa-fire-burner']; // Orange
        case 'Dibatalkan': return ['bg' => '#FFEBEE', 'text' => '#C62828', 'icon' => 'fa-times-circle']; // Merah
        default: return ['bg' => '#F5F5F5', 'text' => '#616161', 'icon' => 'fa-clock'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pesanan Saya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- RESET --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background-color: #F8F9FD; padding-bottom: 100px; }
        a { text-decoration: none; }

        /* --- HEADER --- */
        .header-simple {
            background-color: white; padding: 25px 20px 15px 20px;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
        }
        .page-title { font-size: 22px; font-weight: 700; color: #333; }
        .page-subtitle { font-size: 13px; color: #888; margin-top: 2px; }

        /* --- CONTENT --- */
        .container { padding: 20px; }

        /* --- CARD --- */
        .order-card {
            background-color: white; border-radius: 18px; padding: 18px; margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04); transition: transform 0.1s; position: relative; overflow: hidden;
        }
        .order-card:active { transform: scale(0.98); }

        /* Header Card: No Order & Tanggal */
        .card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        
        .order-id-box { display: flex; align-items: center; gap: 8px; }
        .icon-receipt { 
            width: 32px; height: 32px; background: #E8F5E9; color: #00A859; 
            border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;
        }
        .order-id-text { font-size: 16px; font-weight: 700; color: #333; }
        .order-date { font-size: 11px; color: #999; display: block; margin-top: 2px; }

        /* Status Badge */
        .status-badge {
            padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px;
        }

        /* Divider Dashed */
        .divider { border-bottom: 2px dashed #f0f0f0; margin: 10px 0 15px 0; }

        /* Menu List */
        .menu-list { margin-bottom: 15px; }
        .menu-item { font-size: 13px; color: #555; margin-bottom: 6px; display: flex; justify-content: space-between; }
        .more-items { font-size: 11px; color: #999; font-style: italic; margin-top: 5px; }

        /* Footer Card */
        .card-bottom { display: flex; justify-content: space-between; align-items: center; }
        .label-total { font-size: 11px; color: #888; }
        .price-total { font-size: 16px; font-weight: 700; color: #00A859; }

        /* Action Button */
        .btn-action {
            padding: 10px 20px; border-radius: 10px; font-size: 12px; font-weight: 600; border: none; cursor: pointer;
        }
        .btn-green { background-color: #00A859; color: white; box-shadow: 0 4px 10px rgba(0, 168, 89, 0.3); }
        .btn-outline { background-color: white; color: #00A859; border: 1px solid #00A859; }
        .btn-grey { background-color: #f0f0f0; color: #999; }

        /* Info Pengambilan */
        .pickup-info {
            background-color: #FFF8E1; color: #F57F17; font-size: 11px; padding: 8px 12px;
            border-radius: 8px; margin-top: 12px; display: flex; align-items: center; gap: 8px;
        }

        /* --- EMPTY STATE --- */
        .empty-state { text-align: center; margin-top: 80px; color: #bbb; }

        /* --- BOTTOM NAV --- */
        .bottom-navbar {
            position: fixed; bottom: 0; left: 0; width: 100%; background-color: white; height: 75px;
            display: flex; justify-content: space-between; padding: 0 20px;
            border-top-left-radius: 25px; border-top-right-radius: 25px;
            box-shadow: 0 -5px 30px rgba(0,0,0,0.08); z-index: 100;
        }
        .nav-link { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #C4C4C4; font-size: 11px; font-weight: 500;}
        .nav-link i { font-size: 22px; margin-bottom: 6px; }
        .nav-link.active { color: #00A859; font-weight: 700; }
        .nav-center-wrapper { position: relative; width: 60px; display: flex; justify-content: center; }
        .nav-fab {
            position: absolute; top: -30px; width: 64px; height: 64px; 
            background: linear-gradient(135deg, #00C870, #00A859); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 26px;
            box-shadow: 0 10px 20px rgba(0, 168, 89, 0.4); border: 5px solid #F8F9FD;
        }
    </style>
</head>
<body>

    <div class="header-simple">
        <div class="page-title">Pesanan Saya</div>
        <div class="page-subtitle">Tunjukkan ID Pesanan saat mengambil barang</div>
    </div>

    <div class="container">

        <?php if(empty($riwayat_pesanan)): ?>
            <div class="empty-state">
                <i class="fas fa-receipt" style="font-size: 60px; margin-bottom: 20px; opacity: 0.3;"></i>
                <p>Belum ada riwayat jajan nih.</p>
                <a href="home.php" style="color: #00A859; font-weight: 600; font-size: 14px; margin-top: 10px; display: block;">Mulai Jajan</a>
            </div>
        <?php else: ?>

            <?php foreach($riwayat_pesanan as $order): 
                $style = getStatusColor($order['status']);
            ?>
            <div class="order-card">
                <div class="card-top">
                    <div class="order-id-box">
                        <div class="icon-receipt"><i class="fas fa-receipt"></i></div>
                        <div>
                            <div class="order-id-text"><?= $order['id_order'] ?></div>
                            <span class="order-date"><?= $order['tanggal'] ?></span>
                        </div>
                    </div>
                    <div class="status-badge" style="background: <?= $style['bg'] ?>; color: <?= $style['text'] ?>;">
                        <i class="fas <?= $style['icon'] ?>"></i> <?= $order['status'] ?>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="menu-list">
                    <?php 
                        $max_show = 2;
                        $count = count($order['menu']);
                        for($i=0; $i < min($count, $max_show); $i++) {
                            echo '<div class="menu-item"><span>' . $order['menu'][$i] . '</span></div>';
                        }
                        if($count > $max_show) {
                            echo '<div class="more-items">+ ' . ($count - $max_show) . ' menu lainnya</div>';
                        }
                    ?>
                </div>

                <div class="card-bottom">
                    <div>
                        <div class="label-total">Total Bayar</div>
                        <div class="price-total">Rp <?= number_format($order['total'], 0, ',', '.') ?></div>
                    </div>
                    
                    <?php if($order['status'] == 'Siap Diambil'): ?>
                        <button class="btn-action btn-green">Ambil Sekarang</button>
                    <?php elseif($order['status'] == 'Selesai'): ?>
                        <button class="btn-action btn-outline">Pesan Lagi</button>
                    <?php elseif($order['status'] == 'Diproses'): ?>
                        <button class="btn-action btn-grey" disabled>Menunggu...</button>
                    <?php else: ?>
                        <button class="btn-action btn-grey">Detail</button>
                    <?php endif; ?>
                </div>

                <?php if($order['status'] == 'Siap Diambil'): ?>
                <div class="pickup-info">
                    <i class="fas fa-info-circle"></i> Silakan ke koperasi dan tunjukkan ID ini.
                </div>
                <?php endif; ?>

            </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>

    <nav class="bottom-navbar">
        <a href="home.php" class="nav-link"><i class="fas fa-home"></i><span>Beranda</span></a>
        <a href="pesanan.php" class="nav-link active"><i class="fas fa-receipt"></i><span>Pesanan</span></a>
        <div class="nav-center-wrapper">
            <a href="keranjang_belanja.php" class="nav-fab"><i class="fas fa-shopping-basket"></i></a>
        </div>
        <a href="#" class="nav-link"><i class="fas fa-wallet"></i><span>Saldo</span></a>
        <a href="../profile/profile.php" class="nav-link"><i class="fas fa-user"></i><span>Profil</span></a>
    </nav>

</body>
</html>