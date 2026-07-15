<div class="col-md-3 col-sm-6">
    <div class="menu-card <?= ($menu['status_ketersediaan'] != 'Tersedia') ? 'habis' : '' ?>">
        
        <?php if ($menu['is_bestseller'] == 1): ?>
            <div class="badge-bestseller"><i class="fa fa-star"></i> BEST SELLER</div>
        <?php endif; ?>

        <?php if ($menu['status_ketersediaan'] != 'Tersedia'): ?>
            <div class="badge-habis">STOK HABIS</div>
        <?php endif; ?>

        <div class="img-wrapper">
            <?php $foto = ($menu['foto_menu']) ? $menu['foto_menu'] : 'default_menu.jpg'; ?>
            <img src="<?= base_url('img/menu/' . $foto) ?>" class="menu-img" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x300/eaddd3/3e2723?text=Foto+Menyusul';">
        </div>
        
        <div class="menu-content">
            <div>
                <div class="menu-title" title="<?= htmlspecialchars($menu['nama_menu']) ?>"><?= htmlspecialchars($menu['nama_menu']) ?></div>
                <div class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></div>
            </div>
            
            <?php if ($sudah_reservasi): ?>
                <?php if ($menu['status_ketersediaan'] == 'Tersedia'): ?>
                    <a href="<?= base_url('keranjang?add=' . $menu['id_menu']) ?>" class="btn btn-add-cart">Tambah ke Keranjang</a>
                <?php else: ?>
                    <button class="btn btn-add-cart btn-disabled" disabled>Habis Terjual</button>
                <?php endif; ?>
            <?php else: ?>
                <p style="font-size: 11px; color: #bdc3c7; margin: 0; padding-top: 10px;"><i>*Reservasi untuk memesan</i></p>
            <?php endif; ?>
            
        </div>
    </div>
</div>