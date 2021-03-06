<?php 
    include 'init.php';
    $dbAnggota = new anggota;
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>App Tabungan</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= config::base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="<?= config::base_url(); ?>assets/css/tabungan.css" rel="stylesheet">
  </head>

  <div class="container">
    <?php

    $pg = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_STRING);
    switch ($pg) {
         default:
            if(!file_exists("home.php")) die ("file kosong");
            include "home.php";
            break;
        case 'tambah_anggota':
            if(!file_exists("anggota/tambah_anggota.php")) die ("file kosong");
            include "anggota/tambah_anggota.php";
            break;
        case 'detail_anggota':
            if(!file_exists("anggota/anggota_detail.php")) die ("file kosong");
            include "anggota/anggota_detail.php";
            break;
        case 'tabungan_detail':
            if(!file_exists("transaksi/tabungan_detail.php")) die ("file kosong");
            include "transaksi/tabungan_detail.php";
            break;
        case 'admin':
            if(!file_exists("admin/admin.php")) die ("file kosong");
            include "admin/admin.php";
            break;
        case 'admin_detail':
            if(!file_exists("admin/admin_detail.php")) die ("file kosong");
            include "admin/admin_detail.php";
            break;
        case 'tambah_admin':
            if(!file_exists("admin/tambah_admin.php")) die ("file kosong");
            include "admin/tambah_admin.php";
            break;
        case 'login':
            if(!file_exists("login/login.php")) die ("file kosong");
            include "login/login.php";
            break;
    }

    ?>
  </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= config::base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?= config::base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>
