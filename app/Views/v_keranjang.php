<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
?>

<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Kedai Kopi Senja - Keranjang Belanja</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap&subset=latin-ext" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet"> 
        <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/32/924/924514.png" sizes="32x32" />
        <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/owl.carousel.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/reset.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/animate.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">
        <script src="<?= base_url('js/vendor/modernizr-2.8.3.min.js') ?>"></script>

        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= esc($clientKey ?? env('MIDTRANS_CLIENT_KEY')) ?>"></script>

        <style>
            .cart-section { padding: 60px 0; background-color: #fdfbf7; }
            .cart-table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
            .cart-table thead { background-color: #3e2723; color: #fff; }
            .cart-table th { text-align: center; padding: 15px !important; border-bottom: none !important; }
            .cart-table td { vertical-align: middle !important; text-align: center; padding: 20px 15px !important; border-bottom: 1px solid #f0e9e1; }
            .cart-item-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            .product-name { font-family: 'Poppins', sans-serif; font-weight: 600; color: #3e2723; font-size: 16px; margin-top: 10px; }
            .qty-input { width: 60px; text-align: center; border: 1px solid #d7ccc8; border-radius: 5px; padding: 5px; outline: none; }
            .qty-input:focus { border-color: #8d6e63; }
            .btn-remove { color: #e74c3c; font-size: 20px; transition: 0.3s; }
            .btn-remove:hover { color: #c0392b; transform: scale(1.1); text-decoration: none; }
            
            .summary-card { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid #8d6e63; }
            .summary-card h3 { font-family: 'Righteous', cursive; color: #3e2723; margin-top: 0; margin-bottom: 20px; font-size: 24px; border-bottom: 2px dashed #f0e9e1; padding-bottom: 10px; }
            .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-family: 'Poppins', sans-serif; font-size: 15px; color: #555; }
            .summary-total { font-size: 20px; font-weight: bold; color: #3e2723; border-top: 1px solid #f0e9e1; padding-top: 15px; margin-top: 10px; }
            
            .btn-checkout { background-color: #8d6e63; color: white; width: 100%; padding: 12px; font-size: 16px; font-weight: bold; border: none; border-radius: 5px; transition: 0.3s; text-transform: uppercase; }
            .btn-checkout:hover { background-color: #3e2723; color: #f1c40f; }
            .btn-continue { background-color: transparent; color: #8d6e63; border: 2px solid #8d6e63; width: 100%; padding: 10px; font-size: 14px; font-weight: bold; border-radius: 5px; transition: 0.3s; margin-top: 10px; }
            .btn-continue:hover { background-color: #8d6e63; color: white; }
        
            /* CSS TAMBAHAN UNTUK MODAL LOGIN DAN PROFIL DROPDOWN */
            .fixedArea { z-index: 9999 !important; position: fixed; width: 100%; }
            .profile-dropdown .dropdown-menu { background-color: #333; border: none; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); margin-top: 10px; }
            .profile-dropdown .dropdown-menu > li > a { color: #fff; padding: 10px 20px; transition: 0.2s; }
            .profile-dropdown .dropdown-menu > li > a:hover { background-color: #555; color: #f1c40f; }
            .custom-modal-width { max-width: 400px; width: 100%; margin: 100px auto; }
            .custom-login-modal { border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
            .custom-login-header { background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80') center center / cover; position: relative; padding: 40px 20px 25px; text-align: center; border-bottom: none; }
            .custom-login-header::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.85); }
            .custom-login-header .close { position: absolute; top: 15px; right: 20px; color: #fff; opacity: 0.8; text-shadow: none; z-index: 10; font-size: 28px; }
            .custom-login-header .close:hover { opacity: 1; }
            .custom-login-title { position: relative; z-index: 2; color: #fff; font-family: 'Righteous', cursive; font-size: 28px; margin-bottom: 5px; letter-spacing: 1px; }
            .custom-login-subtitle { position: relative; z-index: 2; color: #f1c40f; font-family: 'Poppins', sans-serif; font-size: 13px; }
            .custom-login-body { padding: 30px 40px 40px; background-color: #fdfbf7; }
            .custom-login-body .form-group label { font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600; margin-bottom: 8px; display: block; }
            .custom-input { border-radius: 10px; border: 1px solid #d7ccc8; padding: 12px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02); height: auto; }
            .custom-input:focus { border-color: #8d6e63; box-shadow: 0 0 5px rgba(141, 110, 99, 0.2); background-color: #fff; }
            .forgot-pass { float: right; color: #8d6e63; font-size: 12px; font-family: 'Poppins', sans-serif; text-decoration: none; margin-bottom: 20px; font-weight: 600; }
            .forgot-pass:hover { color: #3e2723; text-decoration: underline; }
            .btn-custom-login { background-color: #8d6e63; color: #fff; width: 100%; border-radius: 30px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: none; transition: 0.3s; }
            .btn-custom-login:hover { background-color: #3e2723; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(62, 39, 35, 0.2); }
        </style>
    </head>
    <body>
        
        <header class="top">
            <style>
                .fixedArea { transition: background-color 0.4s ease-in-out, box-shadow 0.4s ease-in-out; }
                .fixedArea.navbar-scrolled { background-color: #333333 !important; box-shadow: 0 4px 10px rgba(0,0,0,0.3) !important; }
                .fixedArea.navbar-scrolled .myNavBar { padding-bottom: 0px !important; padding-top: 0px !important; min-height: auto !important; }
            </style>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    window.addEventListener("scroll", function() {
                        var navArea = document.querySelector(".fixedArea");
                        if (window.scrollY > 50) {
                            navArea.classList.add("navbar-scrolled");
                        } else {
                            navArea.classList.remove("navbar-scrolled");
                        }
                    });
                });
            </script>
            <div class="fixedArea">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 noPadding">
                        <div class="content-wrapper one">
                            <header class="header">
                                <nav class="navbar navbar-default myNavBar">
                                    <div class="container">
                                        <div class="navbar-header">
                                            <div class="row">
                                                <div class="col-md-9 col-sm-9 col-xs-9">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xs-3 col-sm-3">
                                                            <a style="padding-top:0px;" class="navbar-brand navBrandText text-uppercase font-weight-bold" href="<?= base_url('/') ?>"><img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="restorant" width="47" /></a>
                                                        </div>
                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                            <a href="<?= base_url('/') ?>"><img class="img-responsive logo" src="https://placehold.co/200x50/3e2723/ffffff?text=Kopi+Senja" alt="restorant" /></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-3">
                                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                                        <span class="sr-only">Toggle navigation</span>
                                                        <span class="icon-bar"></span>
                                                        <span class="icon-bar"></span>
                                                        <span class="icon-bar"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                         
                                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="nav navbar-nav navbar-right navBar">
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section0" class="nav-link text-uppercase font-weight-bold">Beranda</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section1" class="nav-link text-uppercase font-weight-bold">Promo</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section2" class="nav-link text-uppercase font-weight-bold">Tentang</a></li>
                                                <li class="nav-item"><a href="<?= base_url('menu') ?>" class="nav-link text-uppercase font-weight-bold">Menu</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section5" class="nav-link text-uppercase font-weight-bold">Blog</a></li>
                                                <li class="nav-item"><a href="<?= base_url('reservasi') ?>" class="nav-link text-uppercase font-weight-bold">Reservasi</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section7" class="nav-link text-uppercase font-weight-bold">Lokasi</a></li>
                                                
                                                <?php if ($is_logged_in): ?>
                                                    <li class="nav-item dropdown profile-dropdown">
                                                        <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none;">
                                                            <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" width="22" style="border-radius: 50%; margin-right: 5px; margin-top: -4px;">
                                                            <?= htmlspecialchars($nama_user) ?> <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?= base_url('riwayat_reservasi') ?>" style="color: #d2d2d2;"><i class="fa fa-history" style="margin-right: 8px;"></i> Riwayat Reservasi</a></li>
                                                            <li><a href="<?= base_url('reservasi/riwayat_pemesanan') ?>" style="color: #f1c40f;"><i class="fa fa-shopping-bag" style="margin-right: 8px;"></i> Riwayat Pemesanan</a></li>
                                                            <li><a href="<?= base_url('logout') ?>" style="color: #e74c3c;"><i class="fa fa-sign-out" style="margin-right: 8px;"></i> Keluar</a></li>
                                                        </ul>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <a href="#" data-toggle="modal" data-target="#loginModal" class="nav-link text-uppercase font-weight-bold">Login</a>
                                                    </li>
                                                <?php endif; ?>

                                                <li class="nav-item active">
                                                    <a href="<?= base_url('keranjang') ?>" class="nav-link text-uppercase font-weight-bold" style="font-size: 18px; color: #8d6e63; position: relative;">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <?php 
                                                            $cartLib = new \ci4shoppingcart\Libraries\Cart();
                                                            $cartCount = $cartLib->total_items(); 
                                                        ?>
                                                        <?php if ($cartCount > 0): ?>
                                                            <span class="badge" style="position: absolute; top: 5px; right: -5px; background: #e74c3c; color: white; font-size: 10px; border-radius: 50%; padding: 2px 5px;">
                                                                <?= $cartCount ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </header>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1900&q=80'); background-position: center; background-attachment: fixed; padding-bottom: 80px;">
            <div class="container text-center" style="margin-top: 150px; margin-bottom: 50px;">
                <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 50px; text-shadow: 2px 2px 5px rgba(0,0,0,0.7); margin-bottom: 10px;">Keranjang Anda</h2>
                <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">Selesaikan pesanan kopi terbaik Anda.</h4>
            </div>
        </div>

        <section class="cart-section">
            <div class="container">
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif;">
                        <i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif;">
                        <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="table-responsive cart-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="45%">Produk</th>
                                        <th width="20%">Harga</th>
                                        <th width="15%">Kuantitas</th>
                                        <th width="15%">Total</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>

                                <style>
                                    .quick-chip-cart { display: inline-block; background: #fdfbf7; color: #8d6e63; font-size: 10px; padding: 3px 8px; border-radius: 12px; border: 1px solid #eaddd3; cursor: pointer; margin-right: 4px; transition: 0.2s; font-family: 'Poppins', sans-serif; }
                                    .quick-chip-cart:hover { background: #8d6e63; color: white; }
                                </style>

                                <script>
                                    function addNoteCart(chip, rowid) {
                                        var input = document.getElementById('input-note-' + rowid);
                                        var chipText = chip.innerText;
                                        if(input.value.length > 0) { input.value = input.value + ', ' + chipText; } 
                                        else { input.value = chipText; }
                                    }
                                </script>

                                <tbody>
                                    <?php 
                                    if (empty($isi_keranjang)): ?>
                                        <tr>
                                            <td colspan="5" style="padding: 50px !important;">
                                                <i class="fa fa-shopping-basket" style="font-size: 50px; color: #d7ccc8; margin-bottom: 15px;"></i>
                                                <h4 style="font-family: 'Poppins', sans-serif; color: #555;">Keranjang Anda masih kosong.</h4>
                                            </td>
                                        </tr>
                                    <?php else: 
                                        foreach ($isi_keranjang as $item): 
                                            // Semua variabel ini menggunakan key bawaan library shoppingcart
                                            $rowid        = $item['rowid'];
                                            $nama_menu    = $item['name'];
                                            $harga_menu   = $item['price'];
                                            $qty_menu     = $item['qty'];
                                            $subtotal     = $item['subtotal'];
                                            
                                            // Custom data (seperti foto) ditarik dari array 'options'
                                            $foto_menu    = $item['options']['foto_menu'] ?? '';
                                    ?>
                                        <tr>
                                            <td style="text-align: left;">
                                                <div class="row" style="display: flex; align-items: center;">
                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                        <?php 
                                                            if (empty($foto_menu)) {
                                                                $foto_view = 'https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';
                                                            } elseif (strpos($foto_menu, 'http') === 0) {
                                                                $foto_view = $foto_menu; 
                                                            } else {
                                                                $foto_view = base_url('img/menu/' . $foto_menu); 
                                                            }
                                                        ?>
                                                        <img src="<?= $foto_view ?>" class="cart-item-img" alt="Menu" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
                                                    </div>
                                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                                        <div class="product-name"><?= htmlspecialchars($nama_menu) ?></div>

                                                        <form action="<?= base_url('keranjang/update_catatan/' . $rowid) ?>" method="POST" style="margin-top: 8px;">
                                                            <div class="input-group" style="max-width: 250px;">
                                                                <input type="text" 
                                                                    name="catatan_menu" 
                                                                    id="input-note-<?= $rowid ?>" class="form-control" 
                                                                    placeholder="Contoh: Es sedikit..." 
                                                                    value="<?= htmlspecialchars($item['options']['catatan_menu'] ?? '') ?>" 
                                                                    style="border-radius: 5px; font-size: 12px; height: 30px; border: 1px solid #d7ccc8;" 
                                                                    autocomplete="off">
                                                                <span class="input-group-btn">
                                                                    <button type="submit" class="btn btn-default" style="height: 30px; padding: 2px 10px; border-radius: 5px; font-size: 11px; background-color: #f9f5f0; color: #8d6e63; border-color: #d7ccc8;" title="Simpan Catatan">
                                                                        <i class="fa fa-save"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            
                                                            <div style="margin-top: 8px; text-align: left;">
                                                                <span class="quick-chip-cart" onclick="addNoteCart(this, '<?= $rowid ?>')">Gula Sedikit</span>
                                                                <span class="quick-chip-cart" onclick="addNoteCart(this, '<?= $rowid ?>')">Dipisah</span>
                                                                <span class="quick-chip-cart" onclick="addNoteCart(this, '<?= $rowid ?>')">Tanpa Cabai</span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp <?= number_format($harga_menu, 0, ',', '.') ?></td>
                                            <td>
                                                <form action="<?= base_url('keranjang/update_qty/' . $rowid) ?>" method="post">
                                                    <input type="number" 
                                                        name="qty" 
                                                        value="<?= $qty_menu ?>" 
                                                        min="1" 
                                                        onchange="this.form.submit()" 
                                                        class="qty-input">
                                                </form>
                                            </td>
                                            <td style="font-weight: bold; color: #8d6e63;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                            <td>
                                                <a href="<?= base_url('keranjang/hapus/' . $rowid) ?>" class="btn-remove" title="Hapus Item"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; 
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="summary-card">
                            <h3>Ringkasan Pesanan</h3>
                            
                            <?php 
                                $cartLib = new \ci4shoppingcart\Libraries\Cart();
                                $total_semua = $cartLib->total(); 
                                $pajak = $total_semua * 0.10; 
                                $total_akhir = $total_semua + $pajak;
                            ?>

                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>Rp <?= number_format($total_semua, 0, ',', '.') ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Pajak (10%)</span>
                                <span>Rp <?= number_format($pajak, 0, ',', '.') ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Diskon</span>
                                <span style="color: #e74c3c;">- Rp 0</span>
                            </div>
                            
                            <div class="summary-row summary-total">
                                <span>Total Harga</span>
                                <span>Rp <?= number_format($total_akhir, 0, ',', '.') ?></span>
                            </div>

                            <form id="form-checkout" action="<?= base_url('checkout') ?>" method="POST" style="margin-top: 20px;">
                                <button type="submit" id="btn-pay" class="btn-checkout" <?= empty($isi_keranjang) ? 'disabled style="background-color: #bdc3c7; cursor: not-allowed;"' : '' ?>>
                                    <?php if (empty($isi_keranjang)): ?>
                                        <i class="fa fa-lock"></i> Lanjut Pembayaran
                                    <?php else: ?>
                                        <i class="fa fa-credit-card"></i> Lanjut Pembayaran
                                    <?php endif; ?>
                                </button>
                            </form>
                            <a href="<?= base_url('menu') ?>" class="btn btn-continue text-center" style="display: block; text-decoration: none;">
                                <i class="fa fa-arrow-left"></i> Tambah Pesanan Lain
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <footer class="footer-area">
        <div class="container main-footer">

            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-widget pr-60">
                        <div class="footer-logo pb-25">
                            <div class="col-md-4 noPadding text-center">
                                <a class="" href="index.html"><img class="img-responsive" src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="restorant" /></a>
                            </div>
                            <div class="col-md-8 noPadding logo-text">
                                <a class="" href="index.html"><img class="img-responsive" src="https://placehold.co/150x40/3e2723/ffffff?text=Kopi+Senja" alt="restorant" /></a>
                                <p class="colorfullText font-bold">Kedai Kopi Estetik</p>
                            </div>
                        </div>
                        <p>Tempat yang tepat untuk menemukan inspirasi di setiap sruputan. Buka setiap hari melayani kopi dan kehangatan.</p>
                        <div class="footer-social">
                            <ul class="list-group">
                                <li><a href=""><i class="fa fa-facebook"></i></a></li>
                                <li><a href=""><i class="fa fa-pinterest"></i></a></li>
                                <li><a href="#"><i class="fa fa-vimeo"></i></a></li>
                                <li><a href=""><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-widget">
                        <h3>Informasi</h3>
                        <p class="lock"></p>
                        <ul>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2 text-center">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Tentang Kedai</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Kopi Terlaris</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Blog Kami</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Menu Baru</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Kebijakan Privasi</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-widget">
                        <h3>Tautan Berguna</h3>
                        <p class="lock"></p>
                        <ul>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Franchise / Kemitraan</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Lowongan Kerja</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>FAQ</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Promo & Event</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="footer-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="footer-icon"></div>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10">
                                        <a href="">
                                            <p>Pemesanan Katering</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-widget">
                        <h3>Hubungi Kami</h3>
                        <p class="lock"></p>
                        <p>Jl. Kopi Raya No. 42<br>Semarang, Jawa Tengah</p>
                        <p>+62 812 3456 7890<br>+62 898 7654 3210</p>
                        <ul>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding">
                                        <span>Instagram: @kopisenja</span>
                                    </div>
                                </div>
                            </li>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding">
                                        <span>WhatsApp Available</span>
                                    </div>
                                </div>
                            </li>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding">
                                        <span>halo@kopisenja.com</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <p>Copyright © -Kedai Kopi Senja- 2026. Dimodifikasi untuk Tema Kopi.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
            <div class="modal-dialog custom-modal-width" role="document">
                <div class="modal-content custom-login-modal">
                    <div class="custom-login-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h3 class="custom-login-title">Kopi Senja</h3>
                        <p class="custom-login-subtitle">Masuk ke akun Anda</p>
                    </div>
                    <div class="custom-login-body">
                        
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; padding: 10px;">
                                <i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; padding: 10px;">
                                <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('loginAction') ?>" method="POST">
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control custom-input" placeholder="Username" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 5px;">
                                <label>Kata Sandi</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                            </div>
                            <a href="#" class="forgot-pass">Lupa Sandi?</a>
                            <div style="clear: both;"></div>
                            <button type="submit" class="btn btn-custom-login">Masuk Akun</button>
                            <hr>
                            <p class="text-center">Belum punya akun?
                                <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#registerModal">Daftar sekarang</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel">
            <div class="modal-dialog custom-modal-width" role="document">
                <div class="modal-content custom-login-modal">
                    <div class="custom-login-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h3 class="custom-login-title">Daftar Akun</h3>
                    </div>
                    <div class="custom-login-body">
                        <form action="<?= base_url('register_action') ?>" method="POST">
                            
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control custom-input" placeholder="Buat username tanpa spasi" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Email Anda</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="contoh: budi@gmail.com" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Kata Sandi</label>
                                <input type="password" name="password" class="form-control custom-input" placeholder="Buat kata sandi yang aman" required>
                            </div>
                            
                            <button type="submit" class="btn btn-custom-login" style="margin-top: 15px;">Daftar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
        <script src="<?= base_url('js/jquery-easing/jquery.easing.min.js') ?>"></script>
        <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('js/parallax.min.js') ?>"></script>
        <script src="<?= base_url('js/ajax-mail.js') ?>"></script>
        <script src="<?= base_url('js/owl.carousel.min.js') ?>"></script>
        <script src="<?= base_url('js/jquery.nicescroll.min.js') ?>"></script>
        <script src="<?= base_url('js/main.js') ?>"></script>
        
        <?php if(session()->getFlashdata('error')): ?>
            <script>
                $(document).ready(function() { $('#loginModal').modal('show'); });
            </script>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php if(session()->getFlashdata('payment_success')): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Terverifikasi!',
                        text: '<?= session()->getFlashdata('payment_success') ?>',
                        confirmButtonColor: '#8d6e63',
                        confirmButtonText: 'Tutup'
                    });
                });
            </script>
        <?php endif; ?>

        <script>
            $('#form-checkout').submit(function(event) {
                event.preventDefault(); 
                
                var btn = $('#btn-pay');
                var originalText = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success') {
                            snap.pay(response.snapToken, {
                                onSuccess: function(result){
                                    var type = result.payment_type;
                                    var paymentMethod = "Online Payment"; // Nilai default

                                    // Pengelompokan tipe pembayaran secara umum & sederhana
                                    if (type === 'bank_transfer' || type === 'echannel') {
                                        paymentMethod = 'Bank Transfer';
                                    } else if (type === 'qris') {
                                        paymentMethod = 'QRIS';
                                    } else if (type === 'cstore') {
                                        paymentMethod = 'Minimarket';
                                    } else if (type === 'gopay' || type === 'shopeepay' || type === 'emoney') {
                                        paymentMethod = 'E-Wallet';
                                    } else if (type === 'credit_card') {
                                        paymentMethod = 'Kartu Kredit';
                                    }

                                    // Kirim data yang sudah dikelompokkan ke Controller
                                    window.location.href = "<?= base_url('checkout/success') ?>?method=" + encodeURIComponent(paymentMethod);
                                },
                                onPending: function(result){
                                    Swal.fire('Tertunda', 'Pembayaran tertunda. Silakan selesaikan transfer Anda sesuai instruksi di Midtrans.', 'warning');
                                    btn.html(originalText).prop('disabled', false);
                                },
                                onError: function(result){
                                    Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                                    btn.html(originalText).prop('disabled', false);
                                },
                                onClose: function(){
                                    Swal.fire('Dibatalkan', 'Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.', 'info');
                                    btn.html(originalText).prop('disabled', false);
                                }
                            });
                        } else {
                            Swal.fire('Pemberitahuan', response.message, 'warning');
                            btn.html(originalText).prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire('Error Server', 'Terjadi kesalahan pada server. Pastikan Server Key Anda benar.', 'error');
                        btn.html(originalText).prop('disabled', false);
                    }
                });
            });
        </script>
    </body>
</html>