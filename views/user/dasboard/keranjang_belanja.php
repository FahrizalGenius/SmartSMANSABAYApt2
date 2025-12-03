<?php
session_start();

// --- 1. LOGIKA UPDATE KERANJANG (Tambah/Kurang/Hapus) ---
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];

    if (isset($_SESSION['keranjang'][$id])) {
        if ($aksi == 'tambah') {
            $_SESSION['keranjang'][$id]['qty'] += 1;
        } elseif ($aksi == 'kurang') {
            $_SESSION['keranjang'][$id]['qty'] -= 1;
            // Jika qty 0, hapus item
            if ($_SESSION['keranjang'][$id]['qty'] <= 0) {
                unset($_SESSION['keranjang'][$id]);
            }
        } elseif ($aksi == 'hapus') {
            unset($_SESSION['keranjang'][$id]);
        }
    }
    // Refresh halaman agar hitungan terupdate
    header("Location: keranjang_belanja.php");
    exit;
}

// --- 2. HITUNG TOTAL BAYAR ---
$total_bayar = 0;
$total_item = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $item) {
        $total_bayar += ($item['harga'] * $item['qty']);
        $total_item += $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Keranjang - Smart SMANSABAYA</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- RESET & BASIC --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background-color: #F8F9FD; padding-bottom: 120px; /* Ruang untuk footer */ }
        a { text-decoration: none; }

        /* --- HEADER SIMPLE (Putih) --- */
        .header-simple {
            background-color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .btn-back {
            font-size: 18px; color: #333; margin-right: 15px; width: 30px; height: 30px; display: flex; align-items: center;
        }
        .page-title { font-size: 18px; font-weight: 600; color: #333; }

        /* --- CONTENT --- */
        .container { padding: 20px; }

        /* CARD ITEM KERANJANG */
        .cart-item {
            background-color: white;
            border-radius: 15px;
            padding: 15px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            position: relative;
        }

        .item-img {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            background-color: #f0f0f0;
            margin-right: 15px;
        }

        .item-details { flex: 1; }
        .item-name { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 5px; line-height: 1.2; }
        .item-price { font-size: 14px; font-weight: 700; color: #00A859; }

        /* QTY CONTROL (+ -) */
        .qty-control {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 2px;
        }

        .btn-qty {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            background-color: white;
            border-radius: 6px;
            color: #00A859;
            font-size: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-weight: bold;
        }
        
        .btn-qty.min { color: #FF4757; }

        .qty-val {
            width: 30px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-delete {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #ddd;
            font-size: 14px;
        }

        /* --- FOOTER CHECKOUT --- */
        .checkout-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 20px;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
            z-index: 100;
        }

        .total-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .label-total { font-size: 14px; color: #888; }
        .price-total { font-size: 20px; font-weight: 700; color: #00A859; }

        .btn-checkout {
            background: linear-gradient(to right, #00c870, #00A859);
            color: white;
            width: 100%;
            padding: 15px;
            border-radius: 15px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 8px 20px rgba(0, 168, 89, 0.3);
        }

        /* EMPTY STATE */
        .empty-cart {
            text-align: center;
            margin-top: 50px;
            color: #bbb;
        }
        .empty-cart i { font-size: 60px; margin-bottom: 15px; color: #eee; }
    </style>
</head>
<body>

    <div class="header-simple">
        <a href="home.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="page-title">Keranjang Saya</div>
    </div>

    <div class="container">
        
        <?php if (empty($_SESSION['keranjang'])): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <h3>Wah, keranjang kosong</h3>
                <p style="font-size: 12px;">Yuk, isi dengan jajanan kesukaanmu!</p>
                <br>
                <a href="home.php" style="color: #00A859; font-weight: 600;">Kembali Belanja</a>
            </div>

        <?php else: ?>
            <?php foreach ($_SESSION['keranjang'] as $id => $item): ?>
            <div class="cart-item">
                <a href="?aksi=hapus&id=<?= $id ?>" class="btn-delete" onclick="return confirm('Hapus item ini?')">
                    <i class="fas fa-times"></i>
                </a>
                
                <img src="<?= $item['gambar'] ?>" class="item-img" onerror="this.src='https://placehold.co/100?text=Food'">
                
                <div class="item-details">
                    <div class="item-name"><?= $item['nama'] ?></div>
                    <div class="item-price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
                </div>

                <div class="qty-control">
                    <a href="?aksi=kurang&id=<?= $id ?>" class="btn-qty min">
                        <i class="fas fa-minus"></i>
                    </a>
                    <div class="qty-val"><?= $item['qty'] ?></div>
                    <a href="?aksi=tambah&id=<?= $id ?>" class="btn-qty plus">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <?php if (!empty($_SESSION['keranjang'])): ?>
    <div class="checkout-footer">
        <div class="total-info">
            <div class="label-total">Total Pembayaran</div>
            <div class="price-total">Rp <?= number_format($total_bayar, 0, ',', '.') ?></div>
        </div>
        
        <button class="btn-checkout" onclick="prosesBayar()">
            Pesan Sekarang <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
        </button>
    </div>
    <?php endif; ?>

    <script>
        function prosesBayar() {
            Swal.fire({
                title: 'Konfirmasi Pesanan?',
                text: "Total bayar: Rp <?= number_format($total_bayar, 0, ',', '.') ?>",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00A859',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Pesan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Di sini nanti arahkan ke proses penyimpanan database
                    Swal.fire(
                        'Dipesan!',
                        'Pesananmu sedang diproses kantin.',
                        'success'
                    ).then(() => {
                        // Simulasi selesai, kembali ke home atau kosongkan keranjang
                        // window.location = 'proses_checkout.php';
                    });
                }
            })
        }
    </script>

</body>
</html>