<?php
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Riwayat Pemesanan - Kedai Kopi Senja</title>
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
        body { background-color: #fdfbf7; }
        .res-card { background: #fff; border-radius: 15px; padding: 40px 30px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); border-top: 6px solid #8d6e63; margin-top: -80px; position: relative; z-index: 10; margin-bottom: 80px; }
        
        .table-history th { background-color: #f9f5f0; color: #3e2723; font-family: 'Poppins', sans-serif; border-bottom: 2px solid #8d6e63 !important; text-align: center; font-size: 13px; }
        .table-history td { vertical-align: middle !important; font-family: 'Poppins', sans-serif; font-size: 13px; color: #555; text-align: center; }
        
        .badge-success { background-color: #27ae60; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: bold; }
        .payment-method { font-size: 11px; color: #8d6e63; font-weight: bold; display: block; margin-top: 4px; }
        
        .btn-struk { background-color: #3e2723; color: white; border-radius: 5px; font-size: 12px; padding: 6px 15px; border: none; transition: 0.3s; font-family: 'Poppins', sans-serif; font-weight: bold; }
        .btn-struk:hover { background-color: #f1c40f; color: #3e2723; }
        
        .btn-ulasan { background-color: #f39c12; color: white; border-radius: 5px; font-size: 12px; padding: 6px 15px; border: none; transition: 0.3s; font-family: 'Poppins', sans-serif; font-weight: bold; margin-top: 5px; }
        .btn-ulasan:hover { background-color: #d68910; color: white; }

        /* DESAIN BINTANG ULASAN */
        .rating-stars { display: flex; flex-direction: row-reverse; justify-content: center; gap: 5px; }
        .rating-stars input { display: none; }
        .rating-stars label { font-size: 35px; color: #d7ccc8; cursor: pointer; transition: 0.2s; }
        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label { color: #f1c40f; }

        /* DESAIN MODAL STRUK KASIR */
        .modal-struk-body { background: #f4f4f4; padding: 30px 15px; }
        .struk-kertas { background: #fff; width: 100%; max-width: 320px; margin: 0 auto; padding: 25px 20px; font-family: 'Courier New', Courier, monospace; color: #000; box-shadow: 0 5px 15px rgba(0,0,0,0.2); position: relative; }
        .struk-kertas::before, .struk-kertas::after { content: ""; position: absolute; left: 0; width: 100%; height: 10px; background-image: radial-gradient(circle, #f4f4f4 5px, transparent 5px); background-size: 15px 15px; background-repeat: repeat-x; }
        .struk-kertas::before { top: -10px; } .struk-kertas::after { bottom: -10px; }
        
        .struk-header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 15px; margin-bottom: 15px; }
        .struk-header img { width: 40px; margin-bottom: 5px; filter: grayscale(100%); }
        .struk-header h2 { font-weight: bold; font-size: 18px; margin: 0 0 5px 0; }
        .struk-header p { font-size: 12px; margin: 0; line-height: 1.2; }
        
        .struk-info { font-size: 12px; margin-bottom: 15px; line-height: 1.5; }
        .struk-info span { display: inline-block; width: 80px; }
        
        .struk-items { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 15px; }
        .struk-items th { border-bottom: 1px dashed #000; padding-bottom: 5px; text-align: left; }
        .struk-items td { padding: 5px 0; vertical-align: top; }
        .struk-items .td-qty { width: 15%; text-align: center; }
        .struk-items .td-harga { width: 35%; text-align: right; }
        .struk-item-note { font-size: 10px; color: #555; display: block; font-style: italic; }
        
        .struk-total { border-top: 2px dashed #000; padding-top: 10px; margin-top: 5px; font-size: 12px; }
        .struk-total .row-total { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .struk-total .grand-total { font-size: 16px; font-weight: bold; margin-top: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        .struk-footer { text-align: center; font-size: 11px; margin-top: 25px; border-top: 2px dashed #000; padding-top: 15px; }
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
                                                    <span class="sr-only">Toggle navigation</span>
                                                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
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
                                                <li class="nav-item dropdown profile-dropdown active">
                                                    <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none; color: #f1c40f;">
                                                        <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" width="22" style="border-radius: 50%; margin-right: 5px; margin-top: -4px;">
                                                        <?= htmlspecialchars($nama_user) ?> <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?= base_url('riwayat_reservasi') ?>" style="color: #d2d2d2;"><i class="fa fa-calendar-check-o" style="margin-right: 8px;"></i> Riwayat Reservasi</a></li>
                                                        <li><a href="<?= base_url('reservasi/riwayat_pemesanan') ?>" style="color: #f1c40f;"><i class="fa fa-shopping-bag" style="margin-right: 8px;"></i> Riwayat Pemesanan</a></li>
                                                        <li><a href="<?= base_url('logout') ?>" style="color: #e74c3c;"><i class="fa fa-sign-out" style="margin-right: 8px;"></i> Keluar</a></li>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li class="nav-item"><a href="#" data-toggle="modal" data-target="#loginModal" class="nav-link text-uppercase font-weight-bold" style="color: #8d6e63;">Login</a></li>
                                            <?php endif; ?>

                                            <li class="nav-item">
                                                <a href="<?= base_url('keranjang') ?>" class="nav-link text-uppercase font-weight-bold" style="font-size: 18px; color: #8d6e63; position: relative;">
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

    <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1900&q=80'); background-position: center; padding: 220px 0 150px 0; background-attachment: fixed; position: relative;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6);"></div>
        <div class="container text-center" style="position: relative; z-index: 2;">
            <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">Riwayat Pemesanan</h2>
            <p style="color: #f1c40f; font-size: 18px; font-family: 'Poppins', sans-serif;">Detail pesanan kopi dan transaksi Anda.</p>
        </div>
    </div>

    <section>
        <div class="container">
            
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 14px; margin-top: 30px;">
                    <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="res-card">
                        <?php if (empty($riwayat_pemesanan)): ?>
                            <div class="text-center" style="padding: 40px 0;">
                                <i class="fa fa-shopping-basket" style="font-size: 60px; color: #d7ccc8; margin-bottom: 20px;"></i>
                                <h3 style="font-family: 'Righteous', cursive; color: #3e2723;">Belum Ada Pesanan</h3>
                                <p style="font-family: 'Poppins', sans-serif; color: #888;">Anda belum pernah menyelesaikan pembayaran pesanan.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-history">
                                    <thead>
                                        <tr>
                                            <th>ID Transaksi</th>
                                            <th>Pelanggan</th>
                                            <th style="text-align: left;">Ringkasan Pesanan</th>
                                            <th>Total Pembayaran</th>
                                            <th>Status Pembayaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($riwayat_pemesanan as $row): ?>
                                            <tr>
                                                <td><strong style="color: #3e2723;">KDS-<?= $row['id_reservasi'] ?></strong><br><span style="font-size: 11px; color:#888;"><?= date('d M Y', strtotime($row['tanggal_jadwal'])) ?></span></td>
                                                <td><strong><?= htmlspecialchars($nama_user) ?></strong></td>
                                                <td style="text-align: left; line-height: 1.6;">
                                                    <?= $row['ringkasan_menu'] ?>
                                                </td>
                                                <td><strong style="color: #8d6e63; font-size: 14px;">Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?></strong></td>
                                                <td>
                                                    <span class="badge-success"><i class="fa fa-check-circle"></i> Berhasil</span>
                                                    <span class="payment-method">
                                                        <?= htmlspecialchars($row['metode_pembayaran'] ?? 'Online Payment') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                                        <button class="btn-struk" style="width: 100px;" onclick="bukaStruk(<?= $row['id_reservasi'] ?>)">
                                                            <i class="fa fa-print"></i> Detail
                                                        </button>
                                                        
                                                        <?php if (!empty($row['rating'])): ?>
                                                            <div style="margin-top: 8px; color: #f1c40f; font-size: 13px;" title="<?= htmlspecialchars($row['komentar']) ?>">
                                                                <?php for($i=0; $i<$row['rating']; $i++) echo '<i class="fa fa-star"></i>'; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <button class="btn-ulasan" style="width: 100px;" onclick="bukaUlasan(<?= $row['id_reservasi'] ?>)">
                                                                <i class="fa fa-star"></i> Beri Ulasan
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
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
                                <a class="" href="<?= base_url('/') ?>"><img class="img-responsive" src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="restorant" /></a>
                            </div>
                            <div class="col-md-8 noPadding logo-text">
                                <a class="" href="<?= base_url('/') ?>"><img class="img-responsive" src="https://placehold.co/150x40/3e2723/ffffff?text=Kopi+Senja" alt="restorant" /></a>
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
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2 text-center"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Tentang Kedai</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Kopi Terlaris</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Blog Kami</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Menu Baru</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Kebijakan Privasi</p></a></div></div></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-widget">
                        <h3>Tautan Berguna</h3>
                        <p class="lock"></p>
                        <ul>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Franchise / Kemitraan</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Lowongan Kerja</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>FAQ</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Promo & Event</p></a></div></div></li>
                            <li class="footer-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><div class="footer-icon"></div></div><div class="col-md-10 col-sm-10 col-xs-10"><a href=""><p>Pemesanan Katering</p></a></div></div></li>
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
                            <li class="address-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><i class="fa fa-address-card"></i></div><div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding"><span>Instagram: @kopisenja</span></div></div></li>
                            <li class="address-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><i class="fa fa-phone"></i></div><div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding"><span>WhatsApp Available</span></div></div></li>
                            <li class="address-section"><div class="row"><div class="col-md-2 col-sm-2 col-xs-2"><i class="fa fa-envelope"></i></div><div class="col-md-10 col-sm-10 col-xs-10 single-widget-description noPadding"><span>halo@kopisenja.com</span></div></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <div class="container">
                <div class="row"><div class="col-xs-12"><p>Copyright © -Kedai Kopi Senja- 2026. Dimodifikasi untuk Tema Kopi.</p></div></div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="strukModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 400px; margin-top: 80px;">
            <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
                <div class="modal-header" style="border-bottom: none; background: #f4f4f4;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #3e2723; opacity: 0.8; font-size: 28px;">&times;</button>
                </div>
                <div class="modal-struk-body">
                    <div class="struk-kertas">
                        <div class="struk-header">
                            <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="Logo">
                            <h2>KEDAI KOPI SENJA</h2>
                            <p>Jl. Kopi Raya No. 42 Semarang<br>IG: @kopisenja</p>
                        </div>
                        
                        <div class="struk-info">
                            <span>Order ID</span> : <strong id="struk-order-id">-</strong><br>
                            <span>Tanggal</span> : <strong id="struk-tanggal">-</strong><br>
                            <span>Pelanggan</span> : <strong id="struk-pelanggan"><?= htmlspecialchars($nama_user) ?></strong><br>
                            <span>Metode</span> : <strong id="struk-metode">-</strong>
                        </div>
                        
                        <table class="struk-items" id="struk-table-items">
                            </table>
                        
                        <div class="struk-total">
                            <div class="row-total"><span>Subtotal</span><span id="struk-subtotal">Rp 0</span></div>
                            <div class="row-total"><span>Pajak (10%)</span><span id="struk-pajak">Rp 0</span></div>
                            <div class="row-total grand-total"><span>TOTAL DIBAYAR</span><span id="struk-total-akhir">Rp 0</span></div>
                        </div>
                        
                        <div class="struk-footer">
                            <p>LUNAS / PAID<br>Terima kasih atas pesanan Anda!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ulasanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 400px; margin-top: 100px;">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="modal-header" style="border-bottom: none; text-align: center; padding-top: 30px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 15px; font-size: 28px;">&times;</button>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; margin: 0;">Beri Ulasan</h3>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #888; margin-top: 5px;">Bagaimana pengalaman Anda hari ini?</p>
                </div>
                <div class="modal-body" style="padding: 10px 30px 30px 30px;">
                    <form action="<?= base_url('ulasan/simpan') ?>" method="POST">
                        <input type="hidden" name="id_reservasi" id="ulasan_id_reservasi">
                        
                        <div class="text-center" style="margin-bottom: 20px;">
                            <div class="rating-stars">
                                <input type="radio" id="star5" name="rating" value="5" required />
                                <label for="star5" title="5 Bintang"><i class="fa fa-star"></i></label>
                                
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 Bintang"><i class="fa fa-star"></i></label>
                                
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 Bintang"><i class="fa fa-star"></i></label>
                                
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 Bintang"><i class="fa fa-star"></i></label>
                                
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 Bintang"><i class="fa fa-star"></i></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600;">Komentar / Saran (Opsional)</label>
                            <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis pengalaman Anda di sini..." style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; resize: none;"></textarea>
                        </div>
                        
                        <button type="submit" style="background-color: #8d6e63; color: white; border: none; width: 100%; padding: 12px; border-radius: 30px; font-family: 'Poppins', sans-serif; font-weight: bold; margin-top: 10px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#3e2723'" onmouseout="this.style.backgroundColor='#8d6e63'">Kirim Ulasan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        function bukaUlasan(id_reservasi) {
            $('#ulasan_id_reservasi').val(id_reservasi);
            $('#ulasanModal').modal('show');
        }

        function bukaStruk(id_reservasi) {
            Swal.fire({ title: 'Menyiapkan Struk...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            $.ajax({
                url: '<?= base_url('reservasi/get_struk/') ?>' + id_reservasi,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if(response.status === 'success') {
                        $('#struk-order-id').text('KDS-' + response.reservasi.id_reservasi);
                        $('#struk-tanggal').text(response.reservasi.tanggal_jadwal);
                        
                        // MENAMPILKAN METODE PEMBAYARAN DINAMIS DI STRUK
                        var metodeBersih = response.reservasi.metode_pembayaran || 'Online Payment';
                        $('#struk-metode').text(metodeBersih);
                        
                        var itemsHtml = '';
                        response.items.forEach(function(item) {
                            itemsHtml += '<tr>';
                            
                            var qtyStr = item.jumlah_pesanan;
                            
                            itemsHtml += '  <td class="td-qty">' + qtyStr + 'x</td>';
                            itemsHtml += '  <td>' + item.nama_menu;
                            if(item.catatan_menu) { itemsHtml += ' <span class="struk-item-note">(' + item.catatan_menu + ')</span>'; }
                            itemsHtml += '  </td>';
                            itemsHtml += '  <td class="td-harga">' + formatRupiah(item.subtotal).replace('Rp', '') + '</td>';
                            itemsHtml += '</tr>';
                        });
                        $('#struk-table-items').html(itemsHtml);
                        
                        $('#struk-subtotal').text(formatRupiah(response.kalkulasi.subtotal));
                        $('#struk-pajak').text(formatRupiah(response.kalkulasi.pajak));
                        $('#struk-total-akhir').text(formatRupiah(response.kalkulasi.total));

                        $('#strukModal').modal('show');
                    } else {
                        Swal.fire('Oops', response.message, 'info');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Gagal mengambil data dari server', 'error');
                }
            });
        }
    </script>
</body>
</html>