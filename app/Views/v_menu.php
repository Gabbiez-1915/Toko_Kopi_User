<?php
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
$sudah_reservasi = $session->get('sudah_reservasi') ? true : false; 

$menuMinuman = [];
$menuMakanan = [];
$menuCemilan = [];

if (!empty($menuData)) {
    foreach ($menuData as $m) {
        $kategori = strtolower($m['kategori']);
        if (strpos($kategori, 'minum') !== false) {
            $menuMinuman[] = $m;
        } elseif (strpos($kategori, 'makan') !== false) {
            $menuMakanan[] = $m;
        } else {
            $menuCemilan[] = $m;
        }
    }
}
?>

<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Kedai Kopi Senja - Menu</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet"> 
        <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/32/924/924514.png" sizes="32x32" />
        <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">

        <style>
            /* CSS KHUSUS HALAMAN MENU */
            .menu-grid { padding: 80px 0; background-color: #fdfbf7; }
            .kategori-title { font-family: 'Righteous', cursive; color: #3e2723; font-size: 32px; text-align: center; margin-bottom: 40px; margin-top: 20px; position: relative; }
            .kategori-title::after { content: ''; display: block; width: 60px; height: 3px; background: #f1c40f; margin: 10px auto 0; }

            /* CSS KARTU MENU */
            .menu-card { background: #fff; border-radius: 16px; margin-bottom: 30px; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.06); border: 1px solid #f0e9e1; position: relative; height: 100%; display: flex; flex-direction: column; }
            .menu-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(62,39,35,0.12); }
            .menu-card.habis .img-wrapper { filter: grayscale(100%); opacity: 0.7; }
            .img-wrapper { width: 100%; height: 220px; background-color: #eaddd3; border-bottom: 1px solid #f9f5f0; overflow: hidden; }
            .menu-img { width: 100%; height: 100%; object-fit: cover; }
            .menu-content { padding: 25px 20px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
            .menu-title { font-family: 'Poppins', sans-serif; font-weight: 700; color: #3e2723; font-size: 18px; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .menu-price { color: #8d6e63; font-weight: bold; font-size: 16px; margin-bottom: 15px; }
            
            /* TOMBOL & BADGE */
            .btn-add-cart { background: #3e2723; color: #fff; border-radius: 25px; padding: 10px 24px; transition: 0.3s; font-size: 13px; font-weight: 600; text-transform: uppercase; border: none; display: block; width: 100%; text-decoration: none; outline: none; }
            .btn-add-cart:hover { background: #8d6e63; color: #fff; text-decoration: none; box-shadow: 0 4px 10px rgba(141,110,99,0.3); }
            .btn-disabled { background: #bdc3c7; color: #fff; cursor: not-allowed; box-shadow: none; }
            .btn-disabled:hover { background: #bdc3c7; color: #fff; }
            .badge-bestseller { position: absolute; top: 15px; left: 15px; background: #f1c40f; color: #3e2723; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-family: 'Poppins', sans-serif; font-weight: bold; z-index: 10; box-shadow: 0 4px 10px rgba(241, 196, 15, 0.4); }
            .badge-habis { position: absolute; top: 15px; right: 15px; background: rgba(231, 76, 60, 0.95); color: white; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-family: 'Poppins', sans-serif; font-weight: bold; z-index: 10; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3); }
            
            /* ALERT RESERVASI */
            .alert-reservasi { background-color: #3e2723; color: #fff; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 50px; font-family: 'Poppins', sans-serif; box-shadow: 0 5px 15px rgba(62,39,35,0.2); border-left: 5px solid #f1c40f; }
            .alert-reservasi h4 { font-family: 'Righteous', cursive; color: #f1c40f; letter-spacing: 1px; margin-bottom: 10px;}
            .alert-reservasi a { color: #f1c40f; font-weight: bold; text-decoration: underline; }
            .alert-reservasi a:hover { color: #fff; }

            /* CSS CHIPS CATATAN */
            .quick-chip { display: inline-block; background: #f9f5f0; color: #8d6e63; font-size: 11px; font-weight: bold; padding: 5px 12px; border-radius: 20px; border: 1px solid #d7ccc8; cursor: pointer; margin-right: 5px; margin-bottom: 8px; transition: 0.2s; font-family: 'Poppins', sans-serif; }
            .quick-chip:hover { background: #8d6e63; color: white; border-color: #8d6e63; transform: scale(1.05); }

            /* CSS ANIMASI FLYING TO CART */
            .img-flying { position: fixed; z-index: 999999; border-radius: 50%; width: 80px; height: 80px; box-shadow: 0 10px 20px rgba(0,0,0,0.3); transition: all 0.8s cubic-bezier(0.25, 0.8, 0.25, 1); object-fit: cover; }
            .cart-bounce { transform: scale(1.4); transition: transform 0.2s; color: #e74c3c !important; }

            /* CSS MODAL LOGIN & UMUM */
            .custom-modal-width { max-width: 400px; width: 100%; margin: 100px auto; }
            .custom-login-modal { border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
            .custom-login-header { background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80') center center / cover; position: relative; padding: 40px 20px 25px; text-align: center; border-bottom: none; }
            .custom-login-header::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.85); }
            .custom-login-header .close { position: absolute; top: 15px; right: 20px; color: #fff; opacity: 0.8; text-shadow: none; z-index: 10; font-size: 28px; }
            .custom-login-header .close:hover { opacity: 1; }
            .custom-login-title { position: relative; z-index: 2; color: #fff; font-family: 'Righteous', cursive; font-size: 28px; margin-bottom: 5px; }
            .custom-login-subtitle { position: relative; z-index: 2; color: #f1c40f; font-family: 'Poppins', sans-serif; font-size: 13px; }
            .custom-login-body { padding: 30px 40px 40px; background-color: #fdfbf7; }
            .custom-input { border-radius: 10px; border: 1px solid #d7ccc8; padding: 12px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; height: auto; }
            .btn-custom-login { background-color: #8d6e63; color: #fff; width: 100%; border-radius: 30px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: bold; text-transform: uppercase; border: none; }
        </style>
    </head>
    <body>
        
        <header class="top">
            <style>
                .fixedArea { transition: background-color 0.4s ease-in-out, box-shadow 0.4s ease-in-out; z-index: 9999 !important; position: fixed; width: 100%; }
                .fixedArea.navbar-scrolled { background-color: #333333 !important; box-shadow: 0 4px 10px rgba(0,0,0,0.3) !important; }
                .fixedArea.navbar-scrolled .myNavBar { padding-bottom: 0px !important; padding-top: 0px !important; min-height: auto !important; }
                .profile-dropdown .dropdown-menu { background-color: #333; border: none; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); margin-top: 10px; }
                .profile-dropdown .dropdown-menu > li > a { color: #fff; padding: 10px 20px; transition: 0.2s; }
                .profile-dropdown .dropdown-menu > li > a:hover { background-color: #555; color: #f1c40f; }
            </style>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    window.addEventListener("scroll", function() {
                        var navArea = document.querySelector(".fixedArea");
                        if (window.scrollY > 50) { navArea.classList.add("navbar-scrolled"); } 
                        else { navArea.classList.remove("navbar-scrolled"); }
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
                                                        <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                                 
                                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="nav navbar-nav navbar-right navBar">
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section0" class="nav-link text-uppercase font-weight-bold">Beranda</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section1" class="nav-link text-uppercase font-weight-bold">Promo</a></li>
                                                <li class="nav-item"><a href="<?= base_url('/') ?>#section2" class="nav-link text-uppercase font-weight-bold">Tentang</a></li>
                                                <li class="nav-item active"><a href="<?= base_url('menu') ?>" class="nav-link text-uppercase font-weight-bold">Menu</a></li>
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

                                                <li class="nav-item">
                                                    <a href="<?= base_url('keranjang') ?>" id="nav-cart-icon" class="nav-link text-uppercase font-weight-bold" style="font-size: 18px; color: #8d6e63; position: relative; transition: 0.3s;">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <span id="cart-badge-container">
                                                            <?= view_cell('\App\Cells\CartCountCell::render') ?>
                                                        </span>
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

        <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1900'); background-position: center; background-attachment: fixed; padding-bottom: 80px;">
            <div class="container text-center" style="margin-top: 150px; margin-bottom: 50px;">
                <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 50px; text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">Daftar Menu Kami</h2>
                <p style="color: #f1c40f; font-size: 20px; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">Pilih rasa favoritmu untuk menemani hari ini.</p>
            </div>
        </div>

        <section class="menu-grid">
            <div class="container">

                <?php if (!$sudah_reservasi): ?>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="alert-reservasi">
                            <i class="fa fa-info-circle" style="font-size: 30px; margin-bottom: 10px; color: #f1c40f;"></i>
                            <h4>Anda Belum Melakukan Reservasi Meja</h4>
                            <p>Untuk memesan ke keranjang, silakan <a href="<?= base_url('reservasi') ?>">lakukan reservasi meja</a> terlebih dahulu. Di bawah ini adalah katalog menu kami.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (empty($menuData)): ?>
                    <div class="col-12 text-center" style="padding: 50px 0;">
                        <h3 style="color: #8d6e63; font-family: 'Poppins', sans-serif;">Belum ada menu yang ditambahkan.</h3>
                    </div>
                <?php else: ?>

                    <?php if (!empty($menuMinuman)): ?>
                    <div class="row">
                        <div class="col-xs-12"><h2 class="kategori-title">Minuman Spesial</h2></div>
                        <?php foreach ($menuMinuman as $menu): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="menu-card <?= ($menu['status_ketersediaan'] != 'Tersedia') ? 'habis' : '' ?>">
                                    <?php if ($menu['is_bestseller'] == 1): ?><div class="badge-bestseller"><i class="fa fa-star"></i> BEST SELLER</div><?php endif; ?>
                                    <?php if ($menu['status_ketersediaan'] != 'Tersedia'): ?><div class="badge-habis">STOK HABIS</div><?php endif; ?>
                                    
                                    <div class="img-wrapper">
                                        <?php 
                                            if (empty($menu['foto_menu'])) {
                                                $foto_view = 'https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';
                                            } elseif (strpos($menu['foto_menu'], 'http') === 0) {
                                                $foto_view = $menu['foto_menu'];
                                            } else {
                                                $foto_view = base_url('img/menu/' . $menu['foto_menu']);
                                            }
                                        ?>
                                        <img src="<?= $foto_view ?>" class="menu-img" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
                                    </div>
                                    <div class="menu-content">
                                        <div>
                                            <div class="menu-title" title="<?= htmlspecialchars($menu['nama_menu']) ?>"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                                            <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                                        </div>
                                        
                                        <?php if ($sudah_reservasi): ?>
                                            <?php if ($menu['status_ketersediaan'] == 'Tersedia'): ?>
                                                <button type="button" class="btn btn-add-cart" 
                                                        data-toggle="modal" 
                                                        data-target="#qtyModal" 
                                                        data-id="<?= $menu['id_menu'] ?>" 
                                                        data-nama="<?= htmlspecialchars($menu['nama_menu']) ?>"
                                                        data-img="<?= $foto_view ?>"
                                                        data-kategori="minuman"
                                                        style="margin-top: 10px;">
                                                    Tambah Keranjang
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-add-cart btn-disabled" style="margin-top: 10px;" disabled>Habis Terjual</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p style="font-size: 11px; color: #bdc3c7; margin: 0; padding-top: 10px;"><i>*Reservasi untuk memesan</i></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($menuMakanan)): ?>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-xs-12"><h2 class="kategori-title">Makanan Utama</h2></div>
                        <?php foreach ($menuMakanan as $menu): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="menu-card <?= ($menu['status_ketersediaan'] != 'Tersedia') ? 'habis' : '' ?>">
                                    <?php if ($menu['is_bestseller'] == 1): ?><div class="badge-bestseller"><i class="fa fa-star"></i> BEST SELLER</div><?php endif; ?>
                                    <?php if ($menu['status_ketersediaan'] != 'Tersedia'): ?><div class="badge-habis">STOK HABIS</div><?php endif; ?>
                                    
                                    <div class="img-wrapper">
                                        <?php 
                                            if (empty($menu['foto_menu'])) {
                                                $foto_view = 'https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';
                                            } elseif (strpos($menu['foto_menu'], 'http') === 0) {
                                                $foto_view = $menu['foto_menu'];
                                            } else {
                                                $foto_view = base_url('img/menu/' . $menu['foto_menu']);
                                            }
                                        ?>
                                        <img src="<?= $foto_view ?>" class="menu-img" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
                                    </div>
                                    <div class="menu-content">
                                        <div>
                                            <div class="menu-title" title="<?= htmlspecialchars($menu['nama_menu']) ?>"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                                            <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                                        </div>
                                        
                                        <?php if ($sudah_reservasi): ?>
                                            <?php if ($menu['status_ketersediaan'] == 'Tersedia'): ?>
                                                <button type="button" class="btn btn-add-cart" 
                                                        data-toggle="modal" 
                                                        data-target="#qtyModal" 
                                                        data-id="<?= $menu['id_menu'] ?>" 
                                                        data-nama="<?= htmlspecialchars($menu['nama_menu']) ?>"
                                                        data-img="<?= $foto_view ?>"
                                                        data-kategori="makanan"
                                                        style="margin-top: 10px;">
                                                    Tambah Keranjang
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-add-cart btn-disabled" style="margin-top: 10px;" disabled>Habis Terjual</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p style="font-size: 11px; color: #bdc3c7; margin: 0; padding-top: 10px;"><i>*Reservasi untuk memesan</i></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($menuCemilan)): ?>
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-xs-12"><h2 class="kategori-title">Cemilan Santai</h2></div>
                        <?php foreach ($menuCemilan as $menu): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="menu-card <?= ($menu['status_ketersediaan'] != 'Tersedia') ? 'habis' : '' ?>">
                                    <?php if ($menu['is_bestseller'] == 1): ?><div class="badge-bestseller"><i class="fa fa-star"></i> BEST SELLER</div><?php endif; ?>
                                    <?php if ($menu['status_ketersediaan'] != 'Tersedia'): ?><div class="badge-habis">STOK HABIS</div><?php endif; ?>
                                    
                                    <div class="img-wrapper">
                                        <?php 
                                            if (empty($menu['foto_menu'])) {
                                                $foto_view = 'https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';
                                            } elseif (strpos($menu['foto_menu'], 'http') === 0) {
                                                $foto_view = $menu['foto_menu'];
                                            } else {
                                                $foto_view = base_url('img/menu/' . $menu['foto_menu']);
                                            }
                                        ?>
                                        <img src="<?= $foto_view ?>" class="menu-img" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
                                    </div>
                                    <div class="menu-content">
                                        <div>
                                            <div class="menu-title" title="<?= htmlspecialchars($menu['nama_menu']) ?>"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                                            <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                                        </div>
                                        
                                        <?php if ($sudah_reservasi): ?>
                                            <?php if ($menu['status_ketersediaan'] == 'Tersedia'): ?>
                                                <button type="button" class="btn btn-add-cart" 
                                                        data-toggle="modal" 
                                                        data-target="#qtyModal" 
                                                        data-id="<?= $menu['id_menu'] ?>" 
                                                        data-nama="<?= htmlspecialchars($menu['nama_menu']) ?>"
                                                        data-img="<?= $foto_view ?>"
                                                        data-kategori="makanan"
                                                        style="margin-top: 10px;">
                                                    Tambah Keranjang
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-add-cart btn-disabled" style="margin-top: 10px;" disabled>Habis Terjual</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p style="font-size: 11px; color: #bdc3c7; margin: 0; padding-top: 10px;"><i>*Reservasi untuk memesan</i></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </section>

        <footer class="footer-area">
            <div class="footer-bottom text-center">
                <div class="container">
                    <div class="row"><div class="col-xs-12"><p style="margin:20px 0;">Copyright © -Kedai Kopi Senja- 2026. Dimodifikasi untuk Tema Kopi.</p></div></div>
                </div>
            </div>
        </footer>

        <div class="modal fade" id="qtyModal" tabindex="-1" role="dialog" aria-labelledby="qtyModalLabel">
            <div class="modal-dialog" role="document" style="max-width: 350px; margin-top: 150px;">
                <div class="modal-content custom-login-modal">
                    <div class="modal-header" style="border-bottom: 1px solid #f0e9e1; padding: 20px 20px 10px;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #3e2723; opacity: 0.8; font-size: 28px; margin-top: -5px;">&times;</button>
                        <h4 class="modal-title" id="menuTitle" style="font-family: 'Righteous', cursive; color: #3e2723; text-align: center; font-size: 22px;">Tambah ke Keranjang</h4>
                    </div>
                    <div class="modal-body" style="background-color: #fdfbf7; padding: 25px;">
                        
                        <form id="form-add-cart" action="<?= base_url('keranjang/tambah') ?>" method="post">
                            <input type="hidden" name="id_menu" id="modalIdMenu">
                            
                            <div class="form-group text-center">
                                <label style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #3e2723; margin-bottom: 10px;">Jumlah Pesanan:</label>
                                <input type="number" name="qty" value="1" min="1" class="form-control" style="width: 100px; margin: 0 auto; text-align: center; border-radius: 10px; border: 1px solid #d7ccc8;">
                            </div>
                            
                            <div class="form-group" style="margin-top: 20px;">
                                <label style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723;">Catatan (Opsional):</label>
                                <input type="text" name="catatan_menu" id="inputCatatan" class="form-control" placeholder="Ketik catatan..." style="border-radius: 10px; border: 1px solid #d7ccc8; font-size: 13px; margin-bottom: 10px;" autocomplete="off">
                                
                                <div id="chipsContainer" style="text-align: center;"></div>
                            </div>
                            
                            <button type="submit" id="btn-submit-cart" class="btn btn-add-cart" style="margin-top: 20px; background-color: #8d6e63; color: white; width: 100%;">Simpan ke Keranjang</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
        <script src="<?= base_url('js/jquery-easing/jquery.easing.min.js') ?>"></script>
        <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('js/main.js') ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <script>
            // Variabel global untuk menyimpan gambar mana yang diklik
            var currentImgSrc = '';

            // Saat Modal Terbuka
            $('#qtyModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); 
                var id = button.data('id'); 
                var nama = button.data('nama'); 
                var kategori = button.data('kategori');
                currentImgSrc = button.data('img'); // Simpan foto untuk animasi
                
                var modal = $(this);
                modal.find('#modalIdMenu').val(id); 
                modal.find('#menuTitle').text(nama);
                modal.find('#inputCatatan').val(''); // Kosongkan catatan sblmnya

                // LOGIKA QUICK CHIPS BERDASARKAN KATEGORI
                var chipsHtml = '';
                if(kategori === 'minuman') {
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Es Sedikit</span>';
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Gula Sedikit</span>';
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Es Dipisah</span>';
                } else {
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Tanpa Irisan Cabai</span>';
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Pedas Sedang</span>';
                    chipsHtml += '<span class="quick-chip" onclick="addChip(this)">Bungkus/Takeaway</span>';
                }
                modal.find('#chipsContainer').html(chipsHtml);
            });

            // FUNGSI KLIK CHIP
            function addChip(element) {
                var input = document.getElementById('inputCatatan');
                var chipText = element.innerText;
                if(input.value.length > 0) {
                    input.value = input.value + ', ' + chipText;
                } else {
                    input.value = chipText;
                }
            }

            // AJAX FORM SUBMIT & ANIMASI TERBANG
            $('#form-add-cart').submit(function(e) {
                e.preventDefault();
                var btn = $('#btn-submit-cart');
                btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...').prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        btn.html('Simpan ke Keranjang').prop('disabled', false);
                        
                        if(response.status === 'success') {
                            $('#qtyModal').modal('hide'); // Tutup Modal
                            
                            // 1. Toast Sweetalert Cantik
                            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true });
                            Toast.fire({ icon: 'success', title: response.message });

                            // 2. Animasi Terbang (Flying to Cart)
                            flyToCart(currentImgSrc);

                            // 3. Update Angka Keranjang secara Realtime
                            setTimeout(function(){
                                var badgeHtml = '<span class="badge" style="position: absolute; top: 5px; right: -5px; background: #e74c3c; color: white; font-size: 10px; border-radius: 50%; padding: 2px 5px;">' + response.cartCount + '</span>';
                                $('#cart-badge-container').html(badgeHtml);
                            }, 800); // Update badge berbarengan saat gambar sampai di keranjang

                        } else {
                            Swal.fire('Oops!', response.message, 'error');
                        }
                    },
                    error: function() {
                        btn.html('Simpan ke Keranjang').prop('disabled', false);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            });

            // LOGIKA EFEK TERBANG
            function flyToCart(imgSrc) {
                var cartIcon = $('#nav-cart-icon');
                
                // Buat kloningan gambar di tengah layar (karena modal di tengah)
                var flyingImg = $('<img src="'+imgSrc+'" class="img-flying">').css({
                    'top': (window.innerHeight / 2) - 40 + 'px',
                    'left': (window.innerWidth / 2) - 40 + 'px'
                }).appendTo('body');

                // Jeda sebentar lalu suruh gambar terbang ke koordinat Ikon Keranjang
                setTimeout(function () {
                    var cartOffset = cartIcon[0].getBoundingClientRect();
                    flyingImg.css({
                        'top': cartOffset.top + 10 + 'px',
                        'left': cartOffset.left + 15 + 'px',
                        'width': '20px',
                        'height': '20px',
                        'opacity': '0'
                    });
                }, 50);

                // Setelah sampai (0.8s), hapus gambar dan buat ikon keranjang memantul
                setTimeout(function () {
                    flyingImg.remove();
                    cartIcon.addClass('cart-bounce');
                    setTimeout(function(){ cartIcon.removeClass('cart-bounce'); }, 300);
                }, 850);
            }
        </script>
    </body>
</html>