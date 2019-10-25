<?php
session_start();
include 'koneksi.php';

//jika tidak ada session pelanggan(blm login,)maka dilarikan ke login.php
if (!isset($_SESSION["pelanggan"]))
{
	echo "<script>alert('Silahkan Login Dahulu');</script>";
	echo "<script>location='login.php';</script>";
}
if(empty($_SESSION["keranjang"]) OR !isset($_SESSION["keranjang"]))
{
	echo "<script>alert('Silahkan isi Keranjang Dahulu, Selamat Berbelanja');</script>";
	echo "<script>location='index.php';</script>";
}
?>

<!DOCTYPE html> 
<html>
<head>
	<title>CHURDINE CRAFT - Checkout</title>
	<link rel="shortcut Icon" href="./img/churdine.jpg">
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
	
<!-- Navbar -->
<?php include'menu.php'; ?>
<br>

<section class="konten">
	<div class="container">
		<h1>Keranjang Belanja</h1>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Barang / Produk</th>
					<th>Foto Produk </th>					
					<th>Ukuran</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Subharga</th>

				</tr>
			</thead>
			<tbody>
				<?php $nomor=1; ?>
				<?php $totalbelanja = 0; ?>
				<?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah): ?>
				<!-- Menampilkan produk yang sedang diperulangkan berdasarkan id produk -->
				<?php
				$ambil = $koneksi->query("SELECT * FROM produk
					WHERE id_produk='$id_produk'");
				$pecah = $ambil->fetch_assoc();
				$subharga = $pecah["harga_produk"]*$jumlah;

				?>
				<tr>
					<td><?php echo $nomor; ?></td>
					<td><?php echo $pecah["nama_produk"]; ?></td>
					<td>
						<img src="./foto_produk/<?php echo $pecah['foto_produk']; ?>" width="120">
					</td> 
					<td><?php echo $pecah["ukuran_produk"]; ?></td>
					<td>Rp. <?php echo number_format($pecah["harga_produk"]); ?></td>
					<td><?php echo $jumlah; ?></td>
					<td>Rp. <?php echo number_format($subharga); ?></td>

				</tr>
				<?php $nomor++; ?>
				<?php $totalbelanja+=$subharga; ?>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="6">Total Belanja</th>
					<th>Rp. <?php echo number_format($totalbelanja) ?></th>
				</tr>
			</tfoot>
		</table>

		<form method="post">
			
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['nama_pelanggan']?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" readonly value="<?php echo $_SESSION["pelanggan"]['no_tlp_pelanggan']?>" class="form-control">
					</div>
				</div>
				<div class="col-md-4">
					<select class="form-control" name="id_ongkir">
						<option value="">Pilih Ongkos Kirim</option>
						<?php
						$ambil = $koneksi->query("SELECT * FROM ongkir");
						while($perongkir = $ambil->fetch_assoc()){
						?>
						<option value="<?php echo $perongkir["id_ongkir"] ?>">
							<?php echo $perongkir['nama_kota'] ?> - 
							Rp. <?php echo number_format($perongkir['tarif']) ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label>Alamat Lengkap Pengiriman</label>
				<textarea class="form-control" name="alamat_pengiriman" placeholder="masukkan alamat lengkap pengiriman (termasuk kode pos)"></textarea>
			</div>
			<button class="btn btn-primary" name="checkout">Beli</button>
		</form>

		<?php
		if (isset($_POST["checkout"]))
		{
			$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
			$id_ongkir = $_POST["id_ongkir"];
			$tanggal_pembelian = date("y,m,d");
			$alamat_pengiriman = $_POST['alamat_pengiriman'];

			$ambil = $koneksi->query("SELECT * FROM ongkir WHERE id_ongkir='$id_ongkir'");
			$arrayongkir = $ambil->fetch_assoc();
			$nama_kota = $arrayongkir['nama_kota'];
			$tarif = $arrayongkir['tarif'];

			$total_pembelian = $totalbelanja + $tarif;

			$max_id = mysqli_query($koneksi, "select max(substring(id_pembelian, 3)) as maximal from pembelian");
			$hasil = mysqli_fetch_array($max_id);

			$hasil2 = (int) ($hasil[0]);

			$id = $hasil2 + 1;

			$id_pembelian = "PB" . sprintf("%04s", $id);

			//1. menyimpan data ke tabel pembelian
			$koneksi->query("INSERT INTO pembelian (
				id_pembelian,id_pelanggan,id_ongkir,tanggal_pembelian,total_pembelian,nama_kota,tarif,alamat_pengiriman)
				VALUES ('$id_pembelian','$id_pelanggan','$id_ongkir','$tanggal_pembelian','$total_pembelian','$nama_kota','$tarif','$alamat_pengiriman') ");

			//2. mendapatkan id_pembelian barusan terjadi
			// $id_pembelian_barusan = $koneksi->insert_id;
			$id_pembelian_barusan = $id_pembelian;
			// var_dump($id_pembelian_barusan);exit();


			foreach ($_SESSION["keranjang"] as $id_produk => $jumlah) 
			{

				// mendapartkan data produk berdasarkan id produk
				$ambil=$koneksi->query("SELECT * FROM produk WHERE id_produk='$id_produk'");
				$perproduk = $ambil->fetch_assoc();

				$nama = $perproduk['nama_produk'];
				$foto = $perproduk['foto_produk'];
				$harga = $perproduk['harga_produk'];
				$ukuran = $perproduk['ukuran_produk'];
				$subharga = $perproduk['harga_produk']*$jumlah;

				$max_id = mysqli_query($koneksi, "select max(substring(id_pembelian_produk, 3)) as maximal from pembelian_produk");
				$hasil = mysqli_fetch_array($max_id);

				$hasil2 = (int) ($hasil[0]);

				$id = $hasil2 + 1;

				$id_pembelian_produk = "PP" . sprintf("%04s", $id);
				$koneksi->query("INSERT INTO pembelian_produk (id_pembelian_produk,id_pembelian,id_produk,nama,foto_produk,harga,ukuran,subharga,jumlah)
					VALUES ('$id_pembelian_produk','$id_pembelian_barusan','$id_produk','$nama','$foto','$harga','$ukuran','$subharga','$jumlah')");
			
				// skrip update stock
				$koneksi->query("UPDATE produk SET stok_produk =stok_produk -$jumlah 
					WHERE id_produk = '$id_produk'");

			}

			//mengkosongkan keranjang belanja
			unset($_SESSION["keranjang"]);
			unset($_SESSION["totaljumlah"]);

			// tampilan dialihkan ke halaman nota, nota dari pembelian yang barusan
			echo "<script>alert('Pembelian Sukses');</script>";
			echo "<script>location='nota.php?id=$id_pembelian_barusan';</script>";
		}
		?>

	</div>
</section>
<!-- <pre><?php print_r($_SESSION['pelanggan']) ?></pre> -->
<!-- <pre><?php print_r($_SESSION["keranjang"]) ?></pre> -->

</body>
</html>