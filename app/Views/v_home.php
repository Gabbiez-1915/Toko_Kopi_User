<?php
// Otomatis mengambil status login, nama, dan status reservasi dari Session
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? ''; 
$sudah_reservasi = $session->get('sudah_reservasi') ? true : false; // Tambahan logika reservasi
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Kedai Kopi Senja - Coffee Shop</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap&subset=latin-ext" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet"> 
        <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/32/924/924514.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/16/924/924514.png" sizes="16x16" />
        <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/owl.carousel.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/reset.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/animate.css') ?>">
        <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">
        <script src="<?= base_url('js/vendor/modernizr-2.8.3.min.js') ?>"></script>

        <style>
            /* CSS KHUSUS KARTU MENU ESTETIK */
            .menu-card { 
                background: #fff; 
                border-radius: 16px; 
                margin: 10px; /* Margin horizontal di slider */
                margin-bottom: 15px; 
                transition: transform 0.3s ease, box-shadow 0.3s ease; 
                overflow: hidden; 
                box-shadow: 0 8px 20px rgba(0,0,0,0.06); 
                border: 1px solid #f0e9e1; 
                position: relative; 
            }
            .menu-card:hover { 
                transform: translateY(-8px); 
                box-shadow: 0 15px 30px rgba(62,39,35,0.12); 
            }
            .menu-card.habis .img-wrapper {
                filter: grayscale(100%); 
                opacity: 0.7;
            }
            .img-wrapper {
                width: 100%;
                height: 220px;
                background-color: #eaddd3; 
                border-bottom: 1px solid #f9f5f0;
                overflow: hidden;
            }
            .menu-img { 
                width: 100%; 
                height: 100%; 
                object-fit: cover; 
            }
            .menu-content { 
                padding: 25px 20px; 
                text-align: center; 
            }
            .menu-title { 
                font-family: 'Poppins', sans-serif; 
                font-weight: 700; 
                color: #3e2723; 
                font-size: 18px; 
                margin-bottom: 8px; 
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .menu-price { 
                color: #8d6e63; 
                font-weight: bold; 
                font-size: 16px; 
                margin-bottom: 20px; 
            }
            .btn-add-cart { 
                background: #3e2723; 
                color: #fff; 
                border-radius: 25px; 
                padding: 10px 24px; 
                transition: 0.3s; 
                font-size: 13px; 
                font-weight: 600;
                text-transform: uppercase; 
                border: none; 
                display: block; 
                width: 100%; /* MEMAKSA TOMBOL MELEBAR PENUH */
                text-align: center; /* MEMASTIKAN TEKS DI TENGAH */
                text-decoration: none; 
                letter-spacing: 0.5px;
            }
            .btn-add-cart:hover { 
                background: #8d6e63; 
                color: #fff; 
                text-decoration: none; 
                box-shadow: 0 4px 10px rgba(141,110,99,0.3);
            }
            .btn-disabled {
                background: #bdc3c7; 
                color: #fff; 
                cursor: not-allowed;
                box-shadow: none;
            }
            .btn-disabled:hover { background: #bdc3c7; color: #fff; }
            .badge-bestseller {
                position: absolute; top: 15px; left: 15px; background: #f1c40f; color: #3e2723; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-family: 'Poppins', sans-serif; font-weight: bold; z-index: 10; box-shadow: 0 4px 10px rgba(241, 196, 15, 0.4);
            }
            .badge-habis {
                position: absolute; top: 15px; right: 15px; background: rgba(231, 76, 60, 0.95); color: white; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-family: 'Poppins', sans-serif; font-weight: bold; z-index: 10; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
            }

            /* CSS FIX UNTUK OWL CAROUSEL (SHADOW & NAVIGASI) */
            #news-slider .owl-stage-outer {
                padding-bottom: 25px; /* Memberi ruang di bawah agar shadow tidak terpotong */
            }
            #news-slider .owl-controls,
            #news-slider .owl-nav,
            #news-slider .owl-dots {
                margin-top: 15px !important; /* Mendorong angka PREV 1 2 NEXT ke bawah */
            }
            
            /* CSS KHUSUS MODAL LOGIN ESTETIK */
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
        
            #section2 {
                padding-bottom: 30px !important; 
            }

            #section3 {
                padding-top: 30px !important;   
            }
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
                                                            <a style="padding-top:0px;" class="navbar-brand navBrandText text-uppercase font-weight-bold" href="#section0"><img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="restorant" width="47" /></a>
                                                        </div>
                                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                                            <a href="#section0"><img class="img-responsive logo" src="https://placehold.co/200x50/3e2723/ffffff?text=Kopi+Senja" alt="restorant" /></a>
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
                                                <li class="nav-item"><a href="#section0" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Beranda <span class="sr-only">(current)</span></a></li>
                                                <li class="nav-item"><a href="#section1" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Promo</a></li>
                                                <li class="nav-item"><a href="#section2" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Tentang</a></li>
                                                <li class="nav-item"><a href="#section3" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Menu</a></li>
                                                <li class="nav-item"><a href="#section5" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Blog</a></li>
                                                <li class="nav-item"><a href="#section6" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Reservasi</a></li>
                                                <li class="nav-item"><a href="#section7" class="nav-link text-uppercase font-weight-bold js-scroll-trigger">Lokasi</a></li>
                                                
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
                                                    <a href="<?= base_url('keranjang') ?>" class="nav-link text-uppercase font-weight-bold js-scroll-trigger" style="font-size: 18px;">
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

        <section id="section0" class="slider-area"> 
            <div class="main-slider owl-theme owl-carousel"> 
                <div class="single-slide item" style="background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1900&q=80')">
                    <div class="slider-content-area">  
                        <div class="row">
                            <div class="slide-content-wrapper text-center">
                                <div class="slide-content">
                                    <img class="classic" src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="47">
                                    <h3>Kedai Kopi Senja </h3>
                                    <h2>Awali Harimu dengan Secangkir Inspirasi</h2>
                                    <p>Nikmati perpaduan biji kopi pilihan terbaik yang diseduh dengan sepenuh hati. Tempat terbaik untuk bersantai dan mencari ide.</p>
                                    <a class="default-btn" href="<?= base_url('keranjang') ?>">Pesan Sekarang</a>
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-slide item" style="background-image: url('https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=1900&q=80')">
                    <div class="slider-content-area">   
                        <div class="row">
                            <div class="slide-content-wrapper text-center">
                                <div class="slide-content">
                                    <img class="classic" src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="47">
                                    <h3>Kedai Kopi Senja </h3>
                                    <h2>Cita Rasa Nusantara di Setiap Tegukan</h2>
                                    <p>Kami menggunakan 100% biji kopi lokal Indonesia yang dipanggang sempurna untuk menghasilkan aroma yang memikat.</p>
                                    <a class="default-btn" href="<?= base_url('keranjang') ?>">Pesan Sekarang</a>
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="single-slide item" style="background-image: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1900&q=80')">
                    <div class="slider-content-area">   
                        <div class="row">
                            <div class="slide-content-wrapper text-center">
                                <div class="slide-content">
                                    <img class="classic" src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="47">
                                    <h3>Kedai Kopi Senja </h3>
                                    <h2>Tempat Nyaman untuk Bertemu</h2>
                                    <p>Fasilitas lengkap dengan WiFi super cepat dan suasana yang mendukung produktivitas kerja maupun tugas kuliah Anda.</p>
                                    <a class="default-btn" href="<?= base_url('keranjang') ?>">Pesan Sekarang</a>
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </section>

        <section id="section1" class="topOff">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body colorfullPanel text-center">
                                <h3>Diskon Hingga 15%</h3>
                                <h2>Promo Spesial <span>Hari Ini</span>
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </h2>
                                <p>Dapatkan potongan harga khusus untuk semua varian kopi susu gula aren dengan menunjukkan kartu pelajar/mahasiswa Anda.</p>                            
                            </div>
                          </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="panel panel-default colorfullParent">
                            <div class="panel-body colorfullPanel text-center">
                                <h3>Baru Pertama Kali Kesini?</h3>
                                <h2><span>Kenali</span> Biji Kopi Kami
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </h2>
                                <p>Barista kami siap membantu Anda memilih jenis kopi yang paling sesuai dengan selera, mulai dari yang ringan hingga ekstra pekat.</p>                            
                            </div>
                          </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body colorfullPanel text-center">
                                <h3>Bawa Pulang Kopi Segar</h3>
                                <h2><span>Beli</span> Biji Kopi Roasting
                                    <img class="classic" src="<?= base_url('img/new/icon.png') ?>">
                                </h2>
                                <p>Kami juga menyediakan biji kopi utuh atau bubuk berkualitas premium yang bisa Anda seduh sendiri di rumah.</p>                            
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="section2">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <div class="maintext text-center">
                            <span>Menyeduh Sejak 2020</span>
                            <h2>Selamat Datang di Kopi Senja</h2>
                            <p>Kami percaya bahwa setiap cangkir kopi memiliki cerita. Berawal dari kecintaan terhadap kopi nusantara, kami menghadirkan ruang hangat untuk menikmati setiap tetes espresso terbaik.</p>                        
                        </div>  
                    </div>
                </div>
                <div class="row shapes">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 minHeightProp">
                                <img class="imgback" src="<?= base_url('img/shape/shape1.png') ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <span>Biji Kopi Pilihan</span>
                                    <p>Biji kopi kami diseleksi langsung dari petani lokal dengan standar kualitas terbaik untuk menjaga konsistensi rasa.</p>                                
                                </div> 
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 minHeightProp">
                                <img class="imgback" src="<?= base_url('img/shape/shape2.png') ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <span>Mesin Espresso Modern</span>
                                    <p>Diekstraksi dengan mesin berteknologi tinggi untuk memastikan krema yang tebal dan rasa yang seimbang di setiap cangkir.</p>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 minHeightProp">
                                <img class="imgback" src="<?= base_url('img/shape/shape3.png') ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <span>Barista Berpengalaman</span>
                                    <p>Setiap cangkir disajikan oleh barista profesional kami yang memahami teknik penyeduhan kopi secara presisi dan penuh seni. </p>
                                </div> 
                            </div> 
                        </div>  
                    </div>
                </div>
            </div>
        </section>

        <section id="section3">
            <div class="container">
                <div class="row">
                     <div class="col-xs-12">
                        <div class="section-title text-center">
                            <h2>Menu Andalan Kopi Senja</h2>
                            <h3>Kualitas Premium</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="col-md-12 noPadding">
                            <div id="news-slider" class="news-slider owl-theme owl-carousel">
                                
                                <?php if (empty($menuData)): ?>
                                    <div class="text-center" style="padding: 50px 0; width: 100%;">
                                        <h3 style="color: #8d6e63; font-family: 'Poppins', sans-serif;">Belum ada menu yang ditambahkan.</h3>
                                    </div>
                                <?php else: ?>
                                    
                                    <?php foreach ($menuData as $menu): ?>
                                        <div class="menu-card <?= ($menu['status_ketersediaan'] != 'Tersedia') ? 'habis' : '' ?>">
                                            
                                            <?php if ($menu['is_bestseller'] == 1): ?>
                                                <div class="badge-bestseller">
                                                    <i class="fa fa-star"></i> BEST SELLER
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($menu['status_ketersediaan'] != 'Tersedia'): ?>
                                                <div class="badge-habis">
                                                    STOK HABIS
                                                </div>
                                            <?php endif; ?>

                                            <!-- Foto Menu Dinamis (Bisa URL atau File Lokal) -->
                                            <div class="img-wrapper">
                                                <?php 
                                                    if (empty($menu['foto_menu'])) {
                                                        $foto_view = 'https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';
                                                    } elseif (strpos($menu['foto_menu'], 'http') === 0) {
                                                        $foto_view = $menu['foto_menu']; // Gunakan link langsung
                                                    } else {
                                                        $foto_view = base_url('img/menu/' . $menu['foto_menu']); // Gunakan folder lokal
                                                    }
                                                ?>
                                                <img src="<?= $foto_view ?>" class="menu-img" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
                                            </div>
                                            
                                            <div class="menu-content">
                                                <div class="menu-title" title="<?= htmlspecialchars($menu['nama_menu']) ?>"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                                                <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
                                                
                                                <!-- LOGIKA TOMBOL RESERVASI DI HOME -->
                                                <?php if ($sudah_reservasi): ?>
                                                    <?php if ($menu['status_ketersediaan'] == 'Tersedia'): ?>
                                                        <a href="<?= base_url('keranjang?add=' . $menu['id_menu']) ?>" class="btn btn-add-cart"><i class="fa fa-shopping-cart" style="margin-right: 5px;"></i> Tambah</a>
                                                    <?php else: ?>
                                                        <button class="btn btn-add-cart btn-disabled" disabled>Habis Terjual</button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p style="font-size: 11px; color: #bdc3c7; margin: 0; padding-top: 10px;"><i>*Reservasi untuk memesan</i></p>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 text-center" style="margin-top: 40px; margin-bottom: 60px;">
                        <a href="<?= base_url('menu') ?>" class="btn-selengkapnya" style="background: #3e2723; color: #fff; padding: 12px 30px; border-radius: 30px; font-weight: bold; display: inline-block;">
                            Lihat Selengkapnya Menu <i class="fa fa-arrow-right" style="margin-left: 8px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="section4" class="parallax-window" data-parallax="scroll" data-image-src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1900&q=80">
            <div style="padding: 80px 0;"> <!-- Tambahan padding agar lebih rapi -->
                <div class="container text-center">
                    <h3>Apa Kata Mereka</h3>
                    <h2>Testimoni Pelanggan</h2>
                    
                    <div class="testimonial-area owl-theme owl-carousel" style="display: block;">
                        <?php foreach ($testimoni as $t): ?>
                            <div class="single-testimonial">
                                <div class="testimonial-info">
                                    <div class="testimonial-content">
                                        <!-- CETAK 5 BINTANG -->
                                        <div style="color: #f1c40f; margin-bottom: 15px; font-size: 20px;">
                                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                        </div>
                                            <p>"<?= htmlspecialchars($t['komentar']) ?>"</p>
                                            <h4><?= htmlspecialchars($t['username']) ?></h4>
                                            <h5>Pelanggan Kedai Kopi Senja</h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

    <section id="section5" class="blog-area" style="background-color: #fdfbf7; padding: 80px 0;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="blog100-form-title container">
                        <h3>Cerita Kopi Senja</h3>
                        <h2>Berita & Artikel Terbaru</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="blog-card">
                        <div class="row m-0" style="display: flex; flex-wrap: wrap;">
                            <div class="col-xs-3 col-sm-3 p-0" style="padding: 0;">
                                <div class="blog-date-badge">
                                    <span class="day">15</span>
                                    <span class="month">Agustus</span>
                                    <span class="year">2026</span>
                                </div>
                            </div>
                            <div class="col-xs-9 col-sm-9">
                                <div class="blog-content">
                                    <h2>Mengenal Perbedaan Kopi Arabica dan Robusta.</h2>
                                    <p>Banyak yang belum tahu bedanya kopi Arabica dan Robusta. Di artikel ini kita akan membahas tingkat keasaman, kafein, dan *notes* rasanya.</p>
                                    <a href="#" class="blog-read-more">Baca Selengkapnya <i class="fa fa-long-arrow-right" style="margin-left: 5px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="blog-card">
                        <div class="row m-0" style="display: flex; flex-wrap: wrap;">
                            <div class="col-xs-3 col-sm-3 p-0" style="padding: 0;">
                                <div class="blog-date-badge" style="background-color: #3e2723;">
                                    <span class="day">02</span>
                                    <span class="month">Sept</span>
                                    <span class="year">2026</span>
                                </div>
                            </div>
                            <div class="col-xs-9 col-sm-9">
                                <div class="blog-content">
                                    <h2>Tips Menyeduh Kopi V60 di Rumah Ala Barista.</h2>
                                    <p>Menyeduh kopi ala *cafe* bisa Anda lakukan sendiri di rumah. Simak panduan rasio air, kopi, serta suhu yang ideal untuk metode V60.</p>
                                    <a href="#" class="blog-read-more">Baca Selengkapnya <i class="fa fa-long-arrow-right" style="margin-left: 5px;"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section6" class="contact">
            <div class="contact100-form-title container">
                <h3>Ingin Tempat Spesial?</h3>
                <h2>Langkah Reservasi Mudah</h2>
                
                <div class="contact100-form">
                    <div class="row res-step-wrapper">
                        
                        <div class="col-md-3 col-sm-6 col-xs-12 res-col">
                            <div class="res-step-card">
                                <i class="fa fa-calendar-check-o res-icon"></i>
                                <h4 class="res-title">1. Tentukan Jadwal</h4>
                                <p class="res-text">Pilih tanggal dan jam kedatangan yang sesuai dengan rencana santai atau meeting Anda.</p>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12 res-col">
                            <div class="res-step-card">
                                <i class="fa fa-users res-icon"></i>
                                <h4 class="res-title">2. Kapasitas Tamu</h4>
                                <p class="res-text">Beri tahu kami jumlah orang yang hadir agar kami menyiapkan meja yang paling nyaman.</p>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12 res-col">
                            <div class="res-step-card">
                                <i class="fa fa-star res-icon-vip"></i>
                                <h4 class="res-title">3. Pilih Kelas</h4>
                                
                                <div class="class-desc-box">
                                    <p><strong>Reguler:</strong> Area semi-outdoor nyaman dengan aroma kopi khas.</p>
                                    <p><strong>VIP <span class="vip-badge">AC</span>:</strong> Ruang private kedap suara untuk meeting eksklusif.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12 res-col">
                            <div class="res-step-card">
                                <i class="fa fa-check-circle res-icon"></i>
                                <h4 class="res-title">4. Konfirmasi</h4>
                                <p class="res-text">Isi form data diri, lalu tunggu tim kami mengonfirmasi bahwa meja siap untuk Anda tempati.</p>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <a href="<?= base_url('reservasi') ?>" class="btn-reservasi-sekarang">
                                Lanjut Isi Form Reservasi <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <section id="section7" class="row address parallax-window" data-parallax="scroll" data-image-src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1900&q=80">
        <div class="col-md-12">
            <div class="row">
                    <div class="col-md-5 col-md-offset-1 addess-description">
                        <span>Lokasi Kami</span>
                        <h2>Alamat Kopi Senja</h2>
                        <p>Kunjungi kedai kami dan nikmati suasana santai dengan seduhan kopi terbaik di kota.</p>
                        <ul>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-address-card"></i>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10 lineHeight">
                                        Utama: Jl. Kopi Raya No. 42, Semarang<br>Cabang: Jl. Senja Indah No. 10, Semarang
                                    </div>
                                </div>
                            </li>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-phone"></i>                                     
                                     </div>
                                     <div class="col-md-10 col-sm-10 col-xs-10 lineHeight">
                                        CS Utama: +62 812 3456 7890<br>Delivery: +62 898 7654 3210
                                    </div>
                                </div>
                            </li>
                            <li class="address-section">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <i class="fa fa-envelope"></i>                                     
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-xs-10 lineHeight">
                                        Email 1: halo@kopisenja.com<br>Email 2: karir@kopisenja.com
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 addess-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d32658821.818401575!2d99.41920736768124!3d-2.2753629505597477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2c4c07d7496404b7%3A0xe37b4de71badf485!2sIndonesia!5e0!3m2!1sen!2sid!4v1658315009264!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                                        <p class="colorfullText font-bold" >Kedai Kopi Estetik</p>
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
                                                <a href=""><p>Tentang Kedai</p></a>
                                            </div>
                                        </div>
                                    </li>                                    
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Kopi Terlaris</p></a>
                                            </div>
                                        </div>
                                    </li>                                
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Blog Kami</p></a>
                                            </div>
                                        </div>
                                    </li> 
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Menu Baru</p></a>
                                            </div>
                                        </div>
                                    </li>                                    
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Kebijakan Privasi</p></a>
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
                                                <a href=""><p>Franchise / Kemitraan</p></a>
                                            </div>
                                        </div>
                                    </li>                                    
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Lowongan Kerja</p></a>
                                            </div>
                                        </div>
                                    </li>                                
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>FAQ</p></a>
                                            </div>
                                        </div>
                                    </li> 
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Promo & Event</p></a>
                                            </div>
                                        </div>
                                    </li>                                    
                                    <li class="footer-section">
                                        <div class="row">
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="footer-icon"></div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href=""><p>Pemesanan Katering</p></a>
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
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyIMWhs-crjT0yhctbRjfJFq75FlEhSzE&callback=initMap"></script>
        
        <?php if(session()->getFlashdata('error') || session()->getFlashdata('success')): ?>
        <script>
            $(document).ready(function() {
                $('#loginModal').modal('show');
            });
        </script>
        <?php endif; ?>

        <script>
            $(window).on('load', function() {
                var owl = $('.testimonial-area');
                
                // Menimpa pengaturan bawaan template dengan aman
                owl.owlCarousel({
                    items: 1,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000, // Slide otomatis tiap 5 detik
                    smartSpeed: 1000, // Animasi transisi yang lebih halus
                    autoplayHoverPause: true,
                    dots: false,
                    nav: false
                });
            });
        </script>

    </body>
</html>