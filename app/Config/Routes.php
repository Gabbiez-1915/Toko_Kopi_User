<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/login', 'AuthController::index');
$routes->post('/loginAction', 'AuthController::loginAction');
$routes->get('/register', 'AuthController::register');
$routes->post('/register_action', 'AuthController::registerAction');
$routes->get('/logout', 'AuthController::logout');

// RUTE KHUSUS ADMIN
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->post('update-status/(:num)', 'AdminController::updateStatus/$1');
});

// ==========================================================
// RUTE KHUSUS PELANGGAN / USER (Di luar grup admin)
// ==========================================================
$routes->post('/simpan_reservasi', 'ReservasiController::simpan_reservasi');

$routes->get('/', 'Home::index');
$routes->get('menu', 'Home::menu');
$routes->get('/reservasi', 'ReservasiController::index');
$routes->post('/batal_reservasi/(:num)', 'ReservasiController::batal_reservasi/$1');
$routes->get('/riwayat_reservasi', 'ReservasiController::riwayat');

// ==========================================================
// ROUTES UNTUK RIWAYAT PEMESANAN & STRUK DIGITAL
// ==========================================================
$routes->get('reservasi/riwayat_pemesanan', 'ReservasiController::riwayat_pemesanan');
$routes->get('reservasi/get_struk/(:num)', 'ReservasiController::get_struk/$1');

// --- RUTE KERANJANG (UPDATE LIBRARY SHOPPINGCART) ---
$routes->get('/keranjang', 'DetailPesananController::keranjang');
$routes->post('/keranjang/tambah', 'DetailPesananController::tambah');
$routes->get('/keranjang/hapus/(:any)', 'DetailPesananController::hapus/$1');
$routes->post('/keranjang/update_qty/(:any)', 'DetailPesananController::update_qty/$1');
$routes->post('/keranjang/update_catatan/(:any)', 'DetailPesananController::update_catatan/$1');
$routes->post('/checkout', 'DetailPesananController::checkout');
$routes->post('ulasan/simpan', 'UlasanController::simpan');

// Rute baru setelah sukses bayar
$routes->get('/checkout/success', 'DetailPesananController::success');

// API Calendar
$routes->get('api/calendar', 'ReservasiController::api_calendar');

// API Google Calendar OAuth
$routes->get('google-calendar/auth/(:num)', 'GoogleCalendarController::auth/$1');
$routes->get('google-calendar/callback', 'GoogleCalendarController::callback');
