<?php
session_start();
//mendapatkan id_produk dari url


// jika sudah ada produk itu dikeranjang, maka produk itu jumlahnya +1
if(isset($_SESSION['keranjang'][$id_produk]))
{
	$_SESSION['keranjang'][$id_produk]+=1;
}
// selain tiu (blm ada di keranjang), maka produk itu dianggap dibeli 1
else
{
	$_SESSION['keranjang'][$id_produk] =1;
}



//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

//larikan ke halaman keranjang
echo "<script>location='keranjang.php';</script>";
?>
