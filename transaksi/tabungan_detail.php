<?php
    $dbLogin = new login;
    if($dbLogin->cek_login_no()) { 
        header("Location: ".config::base_url('index.php?pg=login'));
        die;
    } 
    $dbTabungan = new tabungan;
    $dbAnggota = new anggota;
    $anggota_id = filter_input(INPUT_GET, 'anggota_id', FILTER_SANITIZE_STRING);
    $data_anggota = $dbAnggota->get_one_anggota($anggota_id, 'nama, jml_tabungan');
?>
<div class="col-lg-6 col-lg-offset-3">
    <h2 class="judul text-center mb-10">Detail Tabungan <span class="small"><?= $data_anggota['nama']; ?></span></h2>
    <p class="text-center mb-30">Jumlah Tabungan <strong>Rp <?= number_format($data_anggota['jml_tabungan'],0,',','.'); ?></strong></p>

    <div class="col-lg-12 nopadding-all">
        <select class="mb-10">
            <option selected="">10</option>
        </select>
    </div>

    <div class="col-lg-12 nopadding-all">
        <a href="<?= config::base_url(); ?>" class="btn btn-default pull-right">Kembali!</a>
        <div class="input-group mb-10 pr-5">
            <span class="input-group-addon">Rp</span>
            <input type="text" name="jml_uang" class="form-control" data-jml-uang="" id="jml_uang" placeholder="Jumlah uang...">
            <input type="hidden" name="anggota_id" value="<?= $anggota_id; ?>">
            <div class="input-group-btn">
                <div class="div-loading-btn">
                    <div class="loading-bg btn hidden"></div>
                    <div class="loading-btn hidden"></div>
                    <button class="btn btn-success border-radius-0" id="tambah_tabungan">Tambah</button>
                </div>
                <div class="div-loading-btn">
                    <div class="loading-bg btn hidden"></div>
                    <div class="loading-btn hidden"></div>
                    <button class="btn btn-success" id="ambil_tabungan">Ambil</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 nopadding-all mb-100">
        <div class="list-group">
        <?php
            $transaksis = $dbTabungan->tampil_transaksi('t.jml_uang, t.waktu, t.type, t.waktu, adn.username');
        ?>
            <li class="list-group-item list-group-item-warning">
                <h4 class="list-group-item-heading">Riwayat Transaksi <span class="badge normal"><?= count($transaksis??[]); ?></span></h4>
            </li>
        <?php
            if($transaksis) :
            foreach($transaksis as $t) :
        ?>
            <!-- List group -->
            <li class="list-group-item pl-20 item-hover-yes">
                <p class="list-group-item-text mb-10"><?= date('d M Y, h:i a', $t['waktu']); ?></p>
                <p class="list-group-item-text"><strong>Aksi:</strong> <?= ($t['type']==='add')?'Tambah Tabungan':'Ambil Tabungan'; ?></p>
                <p class="list-group-item-text"><strong>Oleh:</strong> <?= $t['username']; ?></p>
                <span class="badge">Rp <?= number_format($t['jml_uang'],0,',','.'); ?></span>
            </li>
        <?php endforeach; endif; ?>
        </div>
    </div>
</div>
<alert></alert>
<script type="text/javascript">
// generate number format
const inputJml_uang = document.querySelector("input#jml_uang");
inputJml_uang.addEventListener('input', e => {
    let hasil = [];
    let penentu = 3;
    const angka = e.target.value.replace(/\./g, '');
    // set angka asli ke object dataset
    e.target.dataset.jmlUang = angka;
    // number format
    angka.split('').reverse().forEach((angka, i) => {
        if(i === penentu) {
            hasil.unshift(`${angka}.`);
            penentu += 3;
        } else {
            hasil.unshift(angka);
        }
    });
    // set hasil number format di input value
    e.target.value = hasil.join('');
});

// tambah tabungan
const btnTambah_tabungan = document.querySelector("button#tambah_tabungan");
const loading_btnTambah_tabungan = btnTambah_tabungan.previousElementSibling;
const loading_bgTambah_tabungan = loading_btnTambah_tabungan.previousElementSibling;
btnTambah_tabungan.addEventListener('click', () => {
    // loading btn
    loading_btnTambah_tabungan.classList.remove('hidden');
    loading_bgTambah_tabungan.classList.remove('hidden');
    // cek apakah data input ada

    // ambil data
    const jml_uang = document.querySelector('input[name=jml_uang]').dataset.jmlUang;
    const anggota_id = document.querySelector('input[name=anggota_id]').value;

    fetch('<?= config::base_url('transaksi/proses.php?action=tambah_tabungan'); ?>', {
        method: "post",
        headers: {
            'Content-Type':'application/x-www-form-urlencoded'
        },
        body: `jml_uang=${jml_uang}&anggota_id=${anggota_id}`
    })
    .finally(() => {
        // loading btn
        loading_btnTambah_tabungan.classList.add('hidden');
        loading_bgTambah_tabungan.classList.add('hidden');
    })
    // handling errors
    .then(response => {
        if(!response.ok) {
            // set error agar bisa ditangkap oleh catch()
            throw new Error(response.statusText);
        }
        return response.json();
    })
    .then(response => {
        if(response.form_errors !== undefined && response.form_errors.jml_uang !== undefined) {
            document.querySelector('alert').innerHTML = `
            <div class="alert alert-warning alert-dismissible mt-30 fixed" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Peringatan!</strong> ${response.form_errors.jml_uang}.
            </div>`;
            return false;
        }
    })
    .catch(error => {
        document.querySelector('alert').innerHTML = `
        <div class="alert alert-warning alert-dismissible mt-30 fixed" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Peringatan!</strong> Cek koneksi internet kamu lalu coba kembali.
        </div>`;
        return false;
    });
});
</script>