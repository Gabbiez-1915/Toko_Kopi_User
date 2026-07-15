<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter Anda
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
$email_user = $session->get('email') ?? '';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Kedai Kopi Senja - Reservasi Meja</title>
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

        .res-card {
            background: #fff;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border-top: 6px solid #8d6e63;
            margin-top: -80px;
            position: relative;
            z-index: 10;
            margin-bottom: 80px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d7ccc8;
            padding: 15px;
            height: auto;
            box-shadow: none;
            font-family: 'Poppins', sans-serif;
            color: #555;
        }

        .form-control:focus {
            border-color: #8d6e63;
            box-shadow: 0 0 0 0.2rem rgba(141, 110, 99, 0.25);
        }

        .form-group label {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #3e2723;
            margin-bottom: 8px;
        }

        .btn-res {
            background: #3e2723;
            color: #fff;
            width: 100%;
            padding: 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border: none;
            transition: 0.3s;
            margin-top: 20px;
        }

        .btn-res:hover {
            background: #8d6e63;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(141, 110, 99, 0.4);
        }

        .info-box {
            background-color: #f9f5f0;
            border-left: 4px solid #8d6e63;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .info-box p {
            margin: 0;
            color: #555;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        /* CSS KHUSUS MODAL LOGIN & DAFTAR */
        .custom-modal-width { max-width: 400px; width: 100%; margin: 100px auto; }
        .custom-login-modal { border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
        .custom-login-header { background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=600&q=80') center center / cover; position: relative; padding: 40px 20px 25px; text-align: center; border-bottom: none; }
        .custom-login-header::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.85); }
        .custom-login-header .close { position: absolute; top: 15px; right: 20px; color: #fff; opacity: 0.8; text-shadow: none; z-index: 10; font-size: 28px; }
        .custom-login-header .close:hover { opacity: 1; }
        .custom-login-title { position: relative; z-index: 2; color: #fff; font-family: 'Righteous', cursive; font-size: 28px; margin-bottom: 5px; letter-spacing: 1px; }
        .custom-login-subtitle { position: relative; z-index: 2; color: #f1c40f; font-family: 'Poppins', sans-serif; font-size: 13px; }
        .custom-login-body { padding: 30px 40px 40px; background-color: #fdfbf7; }
        .custom-input { border-radius: 10px; border: 1px solid #d7ccc8; padding: 12px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02); }
        .custom-input:focus { border-color: #8d6e63; box-shadow: 0 0 5px rgba(141, 110, 99, 0.2); background-color: #fff; }
        .forgot-pass { float: right; color: #8d6e63; font-size: 12px; font-family: 'Poppins', sans-serif; text-decoration: none; margin-bottom: 20px; font-weight: 600; }
        .forgot-pass:hover { color: #3e2723; text-decoration: underline; }
        .btn-custom-login { background-color: #8d6e63; color: #fff; width: 100%; border-radius: 30px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: none; transition: 0.3s; }
        .btn-custom-login:hover { background-color: #3e2723; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(62, 39, 35, 0.2); }
        
        .table-konfirmasi th { font-weight: 600; color: #3e2723; border: none !important; padding: 8px 0 !important; width: 25%;}
        .table-konfirmasi td { color: #555; border: none !important; padding: 8px 0 !important; word-break: break-word;}
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
                                            <li class="nav-item active"><a href="<?= base_url('reservasi') ?>" class="nav-link text-uppercase font-weight-bold">Reservasi</a></li>
                                            <li class="nav-item"><a href="<?= base_url('/') ?>#section7" class="nav-link text-uppercase font-weight-bold">Lokasi</a></li>

                                            <?php if ($is_logged_in): ?>
                                                <li class="nav-item dropdown profile-dropdown">
                                                    <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none; color: #8d6e63;">
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
                                                    <a href="#" data-toggle="modal" data-target="#loginModal" class="nav-link text-uppercase font-weight-bold" style="color: #8d6e63;">Login</a>
                                                </li>
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
            <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 50px; text-shadow: 2px 2px 8px rgba(0,0,0,0.8); margin-bottom: 15px;">Reservasi Meja</h2>
            <p style="color: #f1c40f; font-size: 20px; font-family: 'Poppins', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">Pastikan tempat Anda aman untuk momen tak terlupakan.</p>
        </div>
    </div>

    <section class="reservation-form">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 col-sm-12">

                    <div class="res-card">

                        <?php 
                        // KONDISI 1: JIKA USER SUDAH LOGIN DAN MEMILIKI TRANSAKSI RESERVASI AKTIF
                        if (isset($cek_reservasi) && in_array($cek_reservasi['status_reservasi'], ['Pending', 'Dikonfirmasi'])): 
                            
                            // LOGIKA PENGECEKAN H-1 PEMBATALAN
                            $tanggal_jadwal = strtotime($cek_reservasi['tanggal_jadwal']);
                            $tanggal_sekarang = strtotime(date('Y-m-d'));
                            // Hitung selisih hari
                            $selisih_hari = ($tanggal_jadwal - $tanggal_sekarang) / (60 * 60 * 24);
                            $bisa_batal = ($selisih_hari >= 1); // True jika masih H-1 atau lebih
                        ?>
                            
                            <div class="text-center" style="padding: 20px 0;">
                                <?php if ($cek_reservasi['status_reservasi'] == 'Pending'): ?>
                                    <i class="fa fa-clock-o fa-spin" style="font-size: 70px; color: #f39c12; margin-bottom: 20px;"></i>
                                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 26px;">Pesanan Sedang Diproses</h3>
                                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 14px; margin-top: 15px; line-height: 1.6;">
                                        Mohon tunggu sebentar, admin kami sedang mencarikan meja terbaik untuk Anda pada tanggal <strong><?= date('d M Y', strtotime($cek_reservasi['tanggal_jadwal'])) ?></strong> jam <strong><?= date('H:i', strtotime($cek_reservasi['waktu_jadwal'])) ?> WIB</strong>.
                                    </p>
                                    <div style="background-color: #fff9e6; border: 1px dashed #f39c12; padding: 12px 25px; border-radius: 30px; margin-top: 25px; display: inline-block;">
                                        <span style="color: #f39c12; font-weight: bold; font-family: 'Poppins', sans-serif;"><i class="fa fa-circle-o-notch fa-spin"></i> Status: Menunggu Konfirmasi Admin</span>
                                    </div>

                                <?php else: ?>
                                    <i class="fa fa-check-circle" style="font-size: 70px; color: #27ae60; margin-bottom: 20px;"></i>
                                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 26px;">Reservasi Berhasil Dikonfirmasi!</h3>
                                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 14px; margin-top: 15px; line-height: 1.6;">
                                        Meja khusus Anda sudah disiapkan dan dikunci oleh petugas. Sampai jumpa pada tanggal <strong><?= date('d M Y', strtotime($cek_reservasi['tanggal_jadwal'])) ?></strong> jam <strong><?= date('H:i', strtotime($cek_reservasi['waktu_jadwal'])) ?> WIB</strong>.
                                    </p>
                                    
                                    <div style="background: linear-gradient(135deg, #8d6e63 0%, #3e2723 100%); color: white; padding: 20px; border-radius: 15px; margin: 30px auto; max-width: 280px; box-shadow: 0 10px 25px rgba(62, 39, 35, 0.25);">
                                        <p style="margin: 0; font-family: 'Poppins', sans-serif; font-size: 11px; color: white; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">Nomor Meja Anda</p>
                                        <h1 style="margin: 5px 0 0 0; font-family: 'Righteous', cursive; font-size: 55px; color: #f1c40f; line-height: 1;">
                                            <?= htmlspecialchars($cek_reservasi['no_meja']) ?>
                                        </h1>
                                    </div>

                                    <a href="<?= base_url('menu') ?>" class="btn btn-custom-login" style="background-color: #27ae60; width: auto; padding: 14px 40px; display: inline-block; font-size: 15px; border-radius: 30px; box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);">
                                        <i class="fa fa-coffee" style="margin-right: 8px;"></i> Lanjut Pilih Menu
                                    </a>

                                    <a href="<?= base_url('google-calendar/auth/' . $cek_reservasi['id_reservasi']) ?>" class="btn btn-custom-login" style="background-color: #4285F4; width: auto; padding: 14px 20px; display: inline-block; font-size: 15px; border-radius: 30px; box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3); margin-left: 10px;">
                                        <i class="fa fa-calendar-plus-o" style="margin-right: 5px;"></i> Simpan ke Kalender Otomatis
                                    </a>
                                <?php endif; ?>

                                <!-- AREA TOMBOL BATAL BOOKING -->
                                <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                                    <?php if ($bisa_batal): ?>
                                        <button type="button" class="btn" data-toggle="modal" data-target="#modalBatalReservasi" style="background-color: transparent; border: 1px solid #e74c3c; color: #e74c3c; border-radius: 20px; padding: 8px 25px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: bold; transition: 0.3s;" onmouseover="this.style.backgroundColor='#e74c3c'; this.style.color='#fff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e74c3c';">
                                            <i class="fa fa-times-circle" style="margin-right: 5px;"></i> Batalkan Booking
                                        </button>
                                        <p style="font-size: 11px; color: #999; margin-top: 8px;">*Anda dapat membatalkan pesanan maksimal H-1.</p>
                                    <?php else: ?>
                                        <!-- Jika hari H, tombol hilang dan diganti teks peringatan -->
                                        <div style="display: inline-block; padding: 10px 20px; background-color: #fdf2f2; border-radius: 8px; border-left: 3px solid #e74c3c;">
                                            <p style="margin: 0; font-size: 12px; color: #c0392b; font-family: 'Poppins', sans-serif;"><i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i> Pembatalan sudah tidak dapat dilakukan (Maksimal H-1 kedatangan).</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        
                        <?php else: ?>
                        <div class="text-center" style="margin-bottom: 30px;">
                            <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="40" style="margin-bottom: 15px;">
                            <h3 style="font-family: 'Righteous', cursive; color: #3e2723;">Formulir Pemesanan Meja</h3>
                        </div>

                        <div class="info-box">
                            <?php if ($is_logged_in): ?>
                                <p><i class="fa fa-info-circle" style="color: #8d6e63; margin-right: 5px;"></i> Harap melakukan reservasi maksimal <strong>H-1</strong> sebelum kedatangan. Notifikasi konfirmasi akan langsung dikirimkan ke <strong>Email Anda</strong>.</p>
                            <?php else: ?>
                                <p><i class="fa fa-info-circle" style="color: #8d6e63; margin-right: 5px;"></i> Harap melakukan reservasi maksimal <strong>H-1</strong> sebelum kedatangan. Anda wajib <strong>Login</strong> terlebih dahulu untuk melanjutkan.</p>
                            <?php endif; ?>
                        </div>

                        <form action="<?= base_url('simpan_reservasi') ?>" method="POST" id="formReservasi">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Pemesan</label>
                                        <input type="text" name="nama_pemesan" class="form-control" placeholder="Nama Lengkap Anda" required <?= $is_logged_in ? 'value="'.htmlspecialchars($nama_user).'"' : '' ?>>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Alamat Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email Anda" required <?= $is_logged_in ? 'value="'.htmlspecialchars($email_user).'"' : '' ?>>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor WhatsApp / HP</label>
                                        <input type="tel" name="whatsapp" class="form-control" placeholder="Contoh: 08123456789" required>
                                    </div>
                                </div>

                                <div class="col-md-6"> 
                                    <div class="form-group">
                                    <label>Jumlah Tamu</label>
                                        <select name="jumlah_tamu" id="jumlahTamu" class="form-control" required>
                                            <option value="" disabled selected>-- Pilih Jumlah Tamu --</option>
                                            <option value="1 - 2 Orang">1 - 2 Orang</option>
                                            <option value="3 - 4 Orang">3 - 4 Orang</option>
                                            <option value="5 - 8 Orang">5 - 8 Orang</option>
                                            <option value="Lebih dari 8 Orang">Lebih dari 8 Orang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Pilihan Kelas Meja</label>
                                        <select name="kelas_meja" id="kelasMeja" class="form-control" required>
                                            <option value="" disabled selected>-- Pilih Kelas / Area --</option>
                                            <option value="reguler">Reguler (Area Semi-Outdoor Nyaman & Santai)</option>
                                            <option value="vip">VIP AC (Ruang Private Kedap Suara untuk Meeting)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Kedatangan</label>
                                        <input type="date" name="tanggal_jadwal" id="tanggalReservasi" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jam Kedatangan</label>
                                        <input type="time" name="waktu_jadwal" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Catatan Khusus (Opsional)</label>
                                        <textarea name="catatan" class="form-control" rows="4" placeholder="Misal: Mohon siapkan kursi bayi, meja dekat jendela, dsb."></textarea>
                                    </div>
                                </div>

                                <?php if ($is_logged_in): ?>
                                    <div class="col-md-6" style="margin-bottom: 10px;">
                                        <button type="button" id="btn-konfirmasi-utama" class="btn btn-custom-login" style="background-color: #3e2723;" onclick="validateAndSubmit('konfirmasi')">
                                            <i class="fa fa-check-circle" style="margin-right: 5px;"></i> Konfirmasi Reservasi
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-custom-login" style="background-color: #8d6e63;" onclick="validateAndSubmit('menu')">
                                            <i class="fa fa-coffee" style="margin-right: 5px;"></i> Lanjut Pilih Menu
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-custom-login" data-toggle="modal" data-target="#loginModal">
                                            <i class="fa fa-lock" style="margin-right: 8px;"></i> Login untuk Reservasi
                                        </button>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </form>

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

    <!-- Modal Konfirmasi Data Reservasi -->
    <div class="modal fade" id="confirmReservasiModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel">
        <div class="modal-dialog" role="document" style="max-width: 550px; width: 100%; margin: 5% auto;">
            <div class="modal-content" style="border-radius: 20px; padding: 25px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-header" style="border-bottom: none; text-align: center; padding-bottom: 0;">
                    <i class="fa fa-calendar-check-o" style="font-size: 50px; color: #8d6e63; margin-bottom: 10px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 24px; margin-top: 0;">Konfirmasi Data</h3>
                </div>
                <div class="modal-body" style="font-family: 'Poppins', sans-serif; color: #555; font-size: 14px;">
                    <p style="text-align: center; margin-bottom: 20px; font-size: 13px; color: #888;">Pastikan data berikut sudah benar sebelum dikirim. Bukti reservasi akan dikirimkan ke email Anda.</p>
                    
                    <div style="background-color: #fdfbf7; padding: 15px; border-radius: 10px; border: 1px solid #f0e9e1;">
                        <table class="table table-borderless table-konfirmasi" style="margin-bottom: 0;">
                            <tr><th>Nama</th><td>: <span id="summ-nama"></span></td></tr>
                            <tr><th>Email</th><td>: <span id="summ-email"></span></td></tr>
                            <tr id="summ-wa-row"><th>WhatsApp</th><td>: <span id="summ-wa"></span></td></tr>
                            <tr><th>Tamu</th><td>: <span id="summ-tamu"></span></td></tr>
                            <tr><th>Kelas</th><td>: <span id="summ-kelas"></span></td></tr>
                            <tr><th>Waktu</th><td>: <span id="summ-waktu"></span></td></tr>
                            <tr><th>Catatan</th><td>: <span id="summ-catatan"></span></td></tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; text-align: center; display: flex; justify-content: space-between; gap: 10px;">
                    <button type="button" class="btn" style="flex: 1; background: #ddd; color: #333; border-radius: 30px; font-weight: bold; font-family: 'Poppins', sans-serif; padding: 12px;" data-dismiss="modal">Batal / Edit</button>
                    <button type="button" class="btn btn-custom-login" style="flex: 1; margin-top: 0;" onclick="submitSimulasiKeberhasilan()">Kirim & Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reservasi Berhasil -->
    <div class="modal fade" id="successReservasiModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel">
        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 15%; width: 350px;">
            <div class="modal-content" style="border-radius: 20px; text-align: center; padding: 40px 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-body">
                    <i class="fa fa-check-circle" style="font-size: 70px; color: #27ae60; margin-bottom: 20px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 24px; margin-bottom: 10px;">Reservasi Berhasil!</h3>
                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                        Terima kasih! Pemesanan meja Anda telah kami simpan.<br><br>
                        Silakan cek <strong>Kotak Masuk Email</strong> Anda secara berkala.
                    </p>
                    <button type="button" class="btn btn-custom-login" style="width: 100%; border-radius: 30px; background-color: #27ae60;" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peringatan -->
    <div class="modal fade" id="warningReservasiModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel">
        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 15%; width: 350px;">
            <div class="modal-content" style="border-radius: 20px; text-align: center; padding: 40px 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-body">
                    <i class="fa fa-exclamation-triangle" style="font-size: 70px; color: #f39c12; margin-bottom: 20px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 22px; margin-bottom: 10px;">Perhatian!</h3>
                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                        Mohon lakukan <strong>Konfirmasi Reservasi</strong> dan simpan pesanan Anda terlebih dahulu sebelum lanjut memilih menu.
                    </p>
                    <button type="button" class="btn btn-custom-login" style="width: 100%; border-radius: 30px; background-color: #f39c12;" data-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
        <div class="modal-dialog custom-modal-width" role="document">
            <div class="modal-content custom-login-modal">
                <div class="custom-login-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="custom-login-title">Kopi Senja</h3>
                    <p class="custom-login-subtitle">Masuk untuk melanjutkan reservasi</p>
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
                            <label style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600;">Username</label>
                            <input type="text" name="username" class="form-control custom-input" placeholder="Username" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600;">Kata Sandi</label>
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
    
    <!-- Modal Register -->
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

    <!-- Modal Konfirmasi Batal Reservasi -->
    <div class="modal fade" id="modalBatalReservasi" tabindex="-1" role="dialog" aria-labelledby="modalBatalLabel">
        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 15%; width: 350px;">
            <div class="modal-content" style="border-radius: 20px; text-align: center; padding: 30px 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-body">
                    <i class="fa fa-question-circle" style="font-size: 60px; color: #e74c3c; margin-bottom: 20px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 22px; margin-bottom: 10px;">Yakin Ingin Batal?</h3>
                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                        Tindakan ini tidak dapat diurungkan. Meja Anda akan dilepas untuk pelanggan lain.
                    </p>
                    
                    <form action="<?= base_url('batal_reservasi/' . (isset($cek_reservasi) ? $cek_reservasi['id_reservasi'] : '')) ?>" method="POST" style="display: flex; gap: 10px;">
                        <button type="button" class="btn" style="flex: 1; background: #ddd; color: #333; border-radius: 20px; font-weight: bold;" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn" style="flex: 1; background: #e74c3c; color: #fff; border-radius: 20px; font-weight: bold;">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // =======================================================
            // 1. LOGIKA KUNCI TANGGAL (MINIMAL H-1 / BESOK)
            // =======================================================
            var besok = new Date();
            besok.setDate(besok.getDate() + 1); // Tambah 1 hari dari hari ini

            var dd = String(besok.getDate()).padStart(2, '0');
            var mm = String(besok.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
            var yyyy = besok.getFullYear();

            // Format tanggal yang diterima input type="date" adalah YYYY-MM-DD
            var tanggalMinimal = yyyy + '-' + mm + '-' + dd;
            
            // Set atribut 'min' pada input kalender
            $('#tanggalReservasi').attr('min', tanggalMinimal);


            // =======================================================
            // 2. LOGIKA JUMLAH TAMU & KELAS MEJA VIP
            // =======================================================
            $('#jumlahTamu').change(function() {
                var jumlah = $(this).val();
                var $kelasMeja = $('#kelasMeja');

                if (jumlah === 'Lebih dari 8 Orang') {
                    $kelasMeja.val('vip');
                    $kelasMeja.find('option[value="reguler"]').prop('disabled', true);
                } else {
                    $kelasMeja.val('reguler');
                    $kelasMeja.find('option[value="reguler"]').prop('disabled', false);
                    $kelasMeja.find('option[value="vip"]').prop('disabled', true);
                }
            });
        });
    </script>

    <script>
        var isConfirmed = false;

        function validateAndSubmit(actionType) {
            var form = document.getElementById('formReservasi');
            
            if (actionType === 'menu') {
                if (!isConfirmed) {
                    $('#warningReservasiModal').modal('show');
                    return; 
                }
                window.location.href = "<?= base_url('menu') ?>"; 
                return;
            }

            if (form.checkValidity()) {
                if (actionType === 'konfirmasi') {
                    if (isConfirmed) {
                        $('#successReservasiModal').modal('show');
                        return;
                    }
                    
                    document.getElementById('summ-nama').innerText = form.elements['nama_pemesan'].value;
                    document.getElementById('summ-email').innerText = form.elements['email'].value;
                    
                    if(form.elements['whatsapp']) {
                        document.getElementById('summ-wa-row').style.display = 'table-row';
                        document.getElementById('summ-wa').innerText = form.elements['whatsapp'].value;
                    } else {
                        document.getElementById('summ-wa-row').style.display = 'none';
                    }
                    
                    var selectTamu = form.elements['jumlah_tamu'];
                    document.getElementById('summ-tamu').innerText = selectTamu.options[selectTamu.selectedIndex].text;
                    
                    var selectKelas = form.elements['kelas_meja'];
                    document.getElementById('summ-kelas').innerText = selectKelas.options[selectKelas.selectedIndex].text;
                    
                    var tgl = form.elements['tanggal_jadwal'].value;
                    var jam = form.elements['waktu_jadwal'].value;
                    document.getElementById('summ-waktu').innerText = tgl + " | " + jam + " WIB";
                    
                    var catatan = form.elements['catatan'].value;
                    document.getElementById('summ-catatan').innerText = catatan ? catatan : "-";

                    $('#confirmReservasiModal').modal('show');
                }
            } else {
                form.reportValidity(); 
            }
        }

        function submitSimulasiKeberhasilan() {
            var form = document.getElementById('formReservasi');
            $('#confirmReservasiModal').modal('hide');
            var btnKonfirm = document.getElementById('btn-konfirmasi-utama');
            btnKonfirm.innerHTML = '<i class="fa fa-spinner fa-spin" style="margin-right: 5px;"></i> Memproses...';
            btnKonfirm.style.cursor = 'not-allowed';
            form.submit();
        }
    </script>

    <?php if(session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
    <script>
        $(document).ready(function() {
            $('#loginModal').modal('show');
        });
    </script>
    <?php endif; ?>

    <?php if(session()->getFlashdata('reservasi_sukses')): ?>
        <script>
            $(document).ready(function() {
                $('#successReservasiModal').modal('show');
        });
        </script>
    <?php endif; ?>

    <div class="modal fade" id="batalSuksesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 15%; width: 350px;">
            <div class="modal-content" style="border-radius: 20px; text-align: center; padding: 40px 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-body">
                    <i class="fa fa-check-circle" style="font-size: 70px; color: #27ae60; margin-bottom: 20px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 24px; margin-bottom: 10px;">Dibatalkan</h3>
                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                        <?= session()->getFlashdata('batal_sukses') ?>
                    </p>
                    <button type="button" class="btn btn-custom-login" style="width: 100%; border-radius: 30px; background-color: #27ae60;" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="batalGagalModal" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 15%; width: 350px;">
            <div class="modal-content" style="border-radius: 20px; text-align: center; padding: 40px 20px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                <div class="modal-body">
                    <i class="fa fa-times-circle" style="font-size: 70px; color: #e74c3c; margin-bottom: 20px;"></i>
                    <h3 style="font-family: 'Righteous', cursive; color: #3e2723; font-size: 24px; margin-bottom: 10px;">Gagal Dibatalkan</h3>
                    <p style="font-family: 'Poppins', sans-serif; color: #666; font-size: 13px; line-height: 1.6; margin-bottom: 25px;">
                        <?= session()->getFlashdata('batal_gagal') ?>
                    </p>
                    <button type="button" class="btn btn-custom-login" style="width: 100%; border-radius: 30px; background-color: #e74c3c;" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php if(session()->getFlashdata('batal_sukses')): ?>
        <script>
            $(document).ready(function() {
                $('#batalSuksesModal').modal('show');
            });
        </script>
    <?php endif; ?>

    <?php if(session()->getFlashdata('batal_gagal')): ?>
        <script>
            $(document).ready(function() {
                $('#batalGagalModal').modal('show');
            });
        </script>
    <?php endif; ?>

</body>

</html>