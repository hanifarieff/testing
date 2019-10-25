<?php
session_start();
//mendapatkan id_produk dari url
$id_produk = $_GET['id'];
$jumlah = $_get['jumlah'];

$_SESSION["keranjang"][$id_produk] = $jumlah;


//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

//larikan ke halaman keranjang
echo "<script>location='keranjang.php';</script>";
?>