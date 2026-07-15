<?php
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
$email_user = $session->get('email') ?? '';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Riwayat Reservasi - Kedai Kopi Senja</title>
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

        /* KARTU KONTEN MENIMPA BANNER */
        .res-card {
            background: #fff;
            border-radius: 15px;
            padding: 50px 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #8d6e63;
            margin-top: -80px;
            position: relative;
            z-index: 10;
            margin-bottom: 80px;
        }

        /* DESAIN TABEL RIWAYAT */
        .table-history th {
            background-color: #f9f5f0;
            color: #3e2723;
            font-family: 'Poppins', sans-serif;
            border-bottom: 2px solid #8d6e63 !important;
            text-align: center;
        }
        .table-history td {
            vertical-align: middle !important;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .bg-pending { background-color: #f39c12; }
        .bg-dikonfirmasi { background-color: #27ae60; }
        .bg-dibatalkan { background-color: #e74c3c; }
        .bg-selesai { background-color: #3498db; }

        /* CSS MODAL LOGIN ESTETIK (Konsisten) */
        .custom-modal-width { max-width: 400px; width: 100%; margin: 100px auto; }
        .custom-login-modal { border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
        .custom-login-header { background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80') center center / cover; position: relative; padding: 40px 20px 25px; text-align: center; border-bottom: none; }
        .custom-login-header::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.85); }
        .custom-login-header .close { position: absolute; top: 15px; right: 20px; color: #fff; opacity: 0.8; text-shadow: none; z-index: 10; font-size: 28px; }
        .custom-login-header .close:hover { opacity: 1; }
        .custom-login-title { position: relative; z-index: 2; color: #fff; font-family: 'Righteous', cursive; font-size: 28px; margin-bottom: 5px; letter-spacing: 1px; }
        .custom-login-subtitle { position: relative; z-index: 2; color: #f1c40f; font-family: 'Poppins', sans-serif; font-size: 13px; }
        .custom-login-body { padding: 30px 40px 40px; background-color: #fdfbf7; }
        .custom-input { border-radius: 10px; border: 1px solid #d7ccc8; padding: 12px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02); height: auto; }
        .custom-input:focus { border-color: #8d6e63; box-shadow: 0 0 5px rgba(141, 110, 99, 0.2); background-color: #fff; }
        .btn-custom-login { background-color: #8d6e63; color: #fff; width: 100%; border-radius: 30px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: none; transition: 0.3s; margin-top: 15px; }
        .btn-custom-login:hover { background-color: #3e2723; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(62, 39, 35, 0.2); }
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
                                                        <li><a href="<?= base_url('riwayat_reservasi') ?>"><i class="fa fa-history" style="margin-right: 8px;"></i> Riwayat Reservasi</a></li>
                                                        <li><a href="<?= base_url('reservasi/riwayat_pemesanan') ?>" style="color: #f1c40f;"><i class="fa fa-shopping-bag" style="margin-right: 8px;"></i> Riwayat Pemesanan</a></li>
                                                        <li><a href="<?= base_url('logout') ?>" style="color: #e74c3c;"><i class="fa fa-sign-out" style="margin-right: 8px;"></i> Keluar</a></li>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li class="nav-item"><a href="#" data-toggle="modal" data-target="#loginModal" class="nav-link text-uppercase font-weight-bold" style="color: #8d6e63;">Login</a></li>
                                            <?php endif; ?>

                                            <li class="nav-item">
                                                <a href="<?= base_url('keranjang') ?>" class="nav-link text-uppercase font-weight-bold" style="font-size: 18px; color: #8d6e63;">
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

    <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1900&q=80'); background-position: center; padding: 220px 0 150px 0; background-attachment: fixed; position: relative;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);"></div>

        <div class="container text-center" style="position: relative; z-index: 2;">
            <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 50px; text-shadow: 2px 2px 8px rgba(0,0,0,0.8); margin-bottom: 15px;">Riwayat Reservasi Anda</h2>
            <p style="color: #f1c40f; font-size: 20px; font-family: 'Poppins', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">Pantau status meja dan jadwal booking Anda.</p>
        </div>
    </div>

    <section class="reservation-form">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12">

                    <?php if(session()->getFlashdata('gcal_error')): ?>
                        <div class="alert alert-danger text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 15px; padding: 15px; margin-bottom: 20px;">
                            <i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('gcal_error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session()->getFlashdata('gcal_success')): ?>
                        <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 15px; padding: 15px; margin-bottom: 20px;">
                            <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('gcal_success') ?>
                        </div>
                    <?php endif; ?>

                    <div class="res-card">
                        <?php if (empty($riwayat_reservasi)): ?>
                            <div class="text-center" style="padding: 40px 0;">
                                <i class="fa fa-calendar-times-o" style="font-size: 60px; color: #d7ccc8; margin-bottom: 20px;"></i>
                                <h3 style="font-family: 'Righteous', cursive; color: #3e2723;">Belum Ada Riwayat</h3>
                                <p style="font-family: 'Poppins', sans-serif; color: #888;">Anda belum pernah melakukan reservasi meja di Kedai Kopi Senja.</p>
                                <a href="<?= base_url('reservasi') ?>" class="btn btn-custom-login" style="background-color: #8d6e63; width: auto; padding: 12px 30px; display: inline-block;">Buat Reservasi Sekarang</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center" style="margin-bottom: 30px;">
                                <h3 style="font-family: 'Righteous', cursive; color: #3e2723;">Daftar Pesanan Meja</h3>
                                <p style="font-family: 'Poppins', sans-serif; color: #888; font-size: 13px;">Berikut adalah riwayat seluruh reservasi yang terhubung dengan akun Anda.</p>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-history">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Tamu</th>
                                            <th>Kelas Meja</th>
                                            <th>No Meja</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($riwayat_reservasi as $row): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <strong style="color: #3e2723;"><?= date('d M Y', strtotime($row['tanggal_jadwal'])) ?></strong><br>
                                                    <span style="font-size: 12px; color: #888;"><i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($row['waktu_jadwal'])) ?> WIB</span>
                                                </td>
                                                <td><?= htmlspecialchars($row['jumlah_tamu']) ?></td>
                                                <td style="text-transform: capitalize; color: #8d6e63; font-weight: bold;"><?= htmlspecialchars($row['kelas_meja']) ?></td>
                                                <td>
                                                    <?php if ($row['no_meja']): ?>
                                                        <span style="background: #fdfbf7; border: 1px solid #eaddd3; padding: 4px 10px; border-radius: 5px; font-weight: bold; color: #3e2723;"><?= htmlspecialchars($row['no_meja']) ?></span>
                                                    <?php else: ?>
                                                        <span style="color: #ccc;">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $status = $row['status_reservasi'];
                                                        $badge_class = 'bg-pending'; 
                                                        if ($status == 'Dikonfirmasi') $badge_class = 'bg-dikonfirmasi';
                                                        if ($status == 'Dibatalkan') $badge_class = 'bg-dibatalkan';
                                                        if ($status == 'Selesai') $badge_class = 'bg-selesai';
                                                    ?>
                                                    <span class="status-badge <?= $badge_class ?>"><?= $status ?></span>
                                                    <?php if ($status == 'Dikonfirmasi'): ?>
                                                        <br>
                                                        <a href="<?= base_url('google-calendar/auth/'.$row['id_reservasi']) ?>" style="display:inline-block; margin-top:5px; font-size: 11px; color: #4285F4; text-decoration: none; font-weight: bold;">
                                                            <i class="fa fa-calendar-plus-o"></i> + Kalender Otomatis
                                                        </a>
                                                    <?php endif; ?>
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
                        <p class="text-center">Belum punya akun? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#registerModal">Daftar sekarang</a></p>
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
                        <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control custom-input" placeholder="Buat username tanpa spasi" required></div>
                        <div class="form-group"><label>Email Anda</label><input type="email" name="email" class="form-control custom-input" placeholder="contoh: budi@gmail.com" required></div>
                        <div class="form-group"><label>Kata Sandi</label><input type="password" name="password" class="form-control custom-input" placeholder="Buat kata sandi yang aman" required></div>
                        <button type="submit" class="btn btn-custom-login">Daftar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    
    <?php if(session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
    <script>
        $(document).ready(function() { $('#loginModal').modal('show'); });
    </script>
    <?php endif; ?>

</body>
</html>